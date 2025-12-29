<?php

namespace App\Models;

use App\Traits\CanMuteEvents;
use App\Traits\Hooks;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class Backup extends Model
{
    use CanMuteEvents, Hooks;

    const MAX_BACKUPS = 30;

    protected string $folder = 'backups';
    protected $fillable = ['file', 'ext', 'size'];

    private array $errors = [];
    private string $tmp = '';

    protected array $excludeTables = ['backups', 'migrations', 'password_reset_tokens', 'jobs', 'failed_jobs', 'job_statuses'];
    protected array $excludeFileNames = ['.', '..', '.gitignore', '.DS_Store', 'readme.md'];
    protected array $backupFiles = [
        'public/robots.txt',
        'storage/app/public/uploads/',
    ];

    const ARCHIVE_PREFIX = 'cms';
    const ARCHIVE_EXT = '.tgz';
    const FOLDER_DB = 'db';

    const HOOK_ON_PROGRESS = 'on_progress';
    const HOOK_ON_SET_PROGRESS_STEP = 'on_set_progress_step';
    const HOOK_AFTER_UN_ARCHIVE = 'after_un_archive';
    const HOOK_AFTER_RESTORE_FILES = 'after_restore_files';
    const HOOK_AFTER_RESTORE_DB = 'after_restore_db';
    const HOOK_RESTORE_FINISH = 'restore_finish';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->folder = base_path($this->folder) . DIRECTORY_SEPARATOR;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function restore(string $path, string $file): void
    {
        try {
            $this->file = 'RESTORE_' . $file;
            $this->tmp = $this->folder . $this->file . DIRECTORY_SEPARATOR;

            $this->unpackArchive($path);
            $this->hookRun(self::HOOK_AFTER_UN_ARCHIVE);

            $this->restoreFiles();
            $this->hookRun(self::HOOK_AFTER_RESTORE_FILES);

            $this->restoreDB();
            $this->hookRun(self::HOOK_AFTER_RESTORE_DB);

        } finally {
            Artisan::call('view:clear');
            File::deleteDirectory($this->tmp);
            Log::info('Restore finished for backup: ' . $path);
        }

        $this->hookRun(self::HOOK_RESTORE_FINISH);
    }

    private function unpackArchive(string $path): void
    {
        $folder = $this->folder;
        exec("tar -xzvf $path -C $folder --no-same-owner", $out, $errors);

        if ($errors || count($out) === 0) {
            $this->errors[] = "Can't open the archive. Error code #" . $errors;
        }
        else {
            File::moveDirectory($folder . $out[ 0 ], $this->tmp);
        }
    }

    private function restoreFiles(): void
    {
        foreach ($this->backupFiles as $relativePath) {
            if ($this->hasErrors()) {
                return;
            }

            $source = $this->tmp . $relativePath;
            $destination = base_path($relativePath);

            if (is_file($source)) {
                File::delete($destination);
                File::copy($source, $destination);
            } else {
                $this->cleanDirectory($destination);
                File::copyDirectory($source, $destination);
            }
        }
    }

    private function cleanDirectory(string $path, bool $deleteIfEmpty = false): void
    {
        $list = File::glob($path . '*');
        $isEmpty = true;

        foreach ($list as $item) {
            if (File::isDirectory($item)) {
                $this->cleanDirectory($item . DIRECTORY_SEPARATOR, true);
            } else {
                if (in_array(basename($item), $this->excludeFileNames)) {
                    $isEmpty = false;
                    continue;
                }
                File::delete($item);
            }
        }

        if ($deleteIfEmpty && $isEmpty) {
            File::deleteDirectory($path);
        }
    }

    private function restoreDB(): void
    {
        if ($this->hasErrors()) {
            return;
        }

        $tables = array_map('current', DB::select('SHOW TABLES'));
        $files = File::files($this->tmp . self::FOLDER_DB);
        $this->hookRun(self::HOOK_ON_SET_PROGRESS_STEP, [ count($files) ]);
        foreach ($files as $path) {
            if ($this->hasErrors()) {
                return;
            }

            $this->hookRun(self::HOOK_ON_PROGRESS);
            $folder = dirname($path) . DIRECTORY_SEPARATOR;
            $table = basename($path, '.sql');

            // Ignore not existed tables
            if (!in_array($table, $tables)) {
                continue;
            }

            $this->restoreDatabaseFile($folder, $table);
        }
    }

    private function restoreDatabaseFile(string $folder, string $table): void
    {
        $sqlFile = rtrim($folder, '/') . '/' . $table . '.sql';

        if (!file_exists($sqlFile)) {
            $this->errors[] = "SQL file not found for table: $table";
            return;
        }

        $escapedTable = "`" . str_replace('`', '``', $table) . "`";

        DB::statement("SET FOREIGN_KEY_CHECKS = 0;");

        try {
            DB::statement("TRUNCATE TABLE $escapedTable;");
        } catch (\Throwable $e) {
            DB::statement("DELETE FROM $escapedTable;");
        }

        DB::statement("SET FOREIGN_KEY_CHECKS = 1;");

        $process = new Process([
            "mysql",
            '--host=' . config('database.connections.mysql.host'),
            '--user=' . config('database.connections.mysql.username'),
            '--password=' . config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            '-e', "source $sqlFile"
        ]);

        $process->setTimeout(0);
        $process->run();

        $output = trim($process->getOutput() . "\n" . $process->getErrorOutput());

        if ($process->getExitCode() !== 0 || !empty($output)) {
            $this->errors[] = "MySQL import error for table $table:\n" . $output;
        }

        $columns = DB::select("SHOW COLUMNS FROM $escapedTable LIKE 'id'");

        if (!empty($columns)) {
            $maxID = (int) DB::table($table)->max('id') + 1;
            DB::statement("ALTER TABLE $escapedTable AUTO_INCREMENT = $maxID;");
        }
    }

    static public function makeBackup(): Backup
    {
        $backup = new self();

        try {
            $backup->file = $backup->generateName();
            $backup->ext = static::ARCHIVE_EXT;
            $backup->tmp = $backup->folder . $backup->file . DIRECTORY_SEPARATOR;

            if (!mkdir($backup->tmp, 0777, true)) {
                $backup->errors[] = "Can't create temporary folder";
            }

            $backup->exportFolders();
            $backup->exportDatabase();
            $backup->createBackupArchive();
        } finally {
            if (!File::deleteDirectory($backup->tmp)) {
                $backup->errors[] = "Can't delete temporary folder";
            }
        }

        if (!$backup->hasErrors()) {
            $backup->size = File::size($backup->getFullPath());
            $backup->save();
        }

        return $backup;
    }

    private function generateName(): string
    {
        return self::ARCHIVE_PREFIX . '_' . Carbon::now()->format('Y-m-d_H-i-s-u');
    }

    private function exportFolders(): void
    {
        File::copy(base_path('.env'), $this->tmp . '.env');

        foreach ($this->backupFiles as $relativePath) {
            if ($this->hasErrors()) {
                return;
            }

            $source = base_path($relativePath);
            if (!file_exists($source)) {
                continue;
            }

            $destination = $this->tmp . $relativePath;
            $makeDir = is_file($source) ? dirname($destination) : $destination;

            if (!is_dir($makeDir) && !mkdir($makeDir, 0755, true)) {
                $this->errors[] = "Couldn't create $destination";
                continue;
            }

            $result = is_file($source)
                ? File::copy($source, $destination)
                : File::copyDirectory($source, $destination);

            if (!$result) {
                $this->errors[] = "Couldn't copy from $relativePath";
            }
        }
    }

    private function exportDatabase(): void
    {
        if ($this->hasErrors()) {
            return;
        }

        $tables = array_map('current', DB::select('SHOW TABLES'));
        $tables = array_filter($tables, fn($table) => !in_array($table, $this->excludeTables));
        $folder = $this->tmp . self::FOLDER_DB . DIRECTORY_SEPARATOR;

        if (!mkdir($folder, 0777, true)) {
            $this->errors[] = "Couldn't create $folder";
            return;
        }

        foreach ($tables as $table) {
            if ($this->hasErrors()) {
                return;
            }

            $this->saveDatabaseFile($folder, $table);
        }
    }

    private function saveDatabaseFile(string $folder, string $table): void
    {
        $existStatus = null;
        $output = null;

        $command = implode(' ', [
            'mysqldump',
            '--host=' . config('database.connections.mysql.host'),
            '--user=' . config('database.connections.mysql.username'),
            '--password=' . preg_replace('/[\'"\\(\\)=]/', "\\\\$0", config('database.connections.mysql.password')),
            '--no-create-info',
            '--no-tablespaces',
            '--complete-insert',
            config('database.connections.mysql.database'),
            $table,
            '> ' . $folder . $table . '.sql',
        ]);

        // mysqldump --host=$host --user=$user --password=$password --no-create-info --no-tablespaces $db $tables > $filename
        exec($command, $output, $existStatus);

        if ($existStatus !== 0) {
            $this->errors[] = "Mysqldump error: \n" . $output;
        }
    }

    public function createBackupArchive(): bool
    {
        $archive = $this->getFullPath();
        $parent = str_replace('\\', DIRECTORY_SEPARATOR, realpath($this->folder));
        $folder = str_replace('\\', DIRECTORY_SEPARATOR, $this->file);
        exec("tar -czf $archive -C $parent ./$folder", $out, $errors);

        return !$errors;
    }

    public function getFullPath(): string
    {
        return $this->folder . $this->getArchiveName();
    }

    public function getArchiveName(): string
    {
        return $this->file . $this->ext;
    }

    public function getHumanSize(): string
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($this->size) - 1) / 3);
        return sprintf("%.2f", $this->size / pow(1024, $factor)) . ' ' . @$size[$factor];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    static public function deleteOldFiles(): void
    {
        $backups = self::orderBy('created_at', 'desc')->skip(self::MAX_BACKUPS)->limit(10)->get();

        foreach ($backups as $backup) {
            $backup->muteEvents('deleted');
            $backup->delete();
            File::delete($backup->getFullPath());
        }
    }
}
