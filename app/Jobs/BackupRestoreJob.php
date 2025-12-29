<?php

namespace App\Jobs;

use App\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Imtigger\LaravelJobStatus\Trackable;

class BackupRestoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, Trackable;

    private int $backupId;
    private int $progress_step = 1;
    public int $tries = 1;
    public int $maxExceptions = 1;
    public int $timeout = 300;

    public function __construct($backupId)
    {
        $this->backupId = $backupId;
        $this->prepareStatus();
        $this->setProgressMax(100);
    }

    public function handle(): void
    {
        $backup = Backup::findOrFail($this->backupId);

        $backup->hookSet(Backup::HOOK_AFTER_UN_ARCHIVE, $this, 'onProgressAfterUnArchive');
        $backup->hookSet(Backup::HOOK_AFTER_RESTORE_FILES, $this, 'onProgressAfterRestoreFiles');
        $backup->hookSet(Backup::HOOK_ON_SET_PROGRESS_STEP, $this, 'onProgressSetStep');
        $backup->hookSet(Backup::HOOK_ON_PROGRESS, $this, 'onProgressGoStep');
        $backup->hookSet(Backup::HOOK_AFTER_RESTORE_DB, $this, 'onProgressAfterRestoreDB');
        $backup->hookSet(Backup::HOOK_RESTORE_FINISH, $this, 'onProgressFinished');

        $backup->restore($backup->getFullPath(), $backup->file);

        if ($backup->hasErrors()) {
            abort(500, implode("\n", $backup->getErrors()));
        }
    }

    public function onProgressAfterUnArchive(): void
    {
        $this->setProgressNow(8);
    }

    public function onProgressAfterRestoreFiles(): void
    {
        $this->setProgressNow(10);
    }

    public function onProgressAfterRestoreDB(): void
    {
        $this->setProgressNow(95);
    }

    public function onProgressSetStep($count): void
    {
        $this->progress_step = 85 / $count;
    }

    public function onProgressGoStep(): void
    {
        $this->incrementProgress($this->progress_step);
    }

    public function onProgressFinished(): void
    {
        $this->setProgressNow(100);
    }

    public function retryUntil(): Carbon
    {
        // Set Job timeout to 10 min
        return now()->addMinutes(10);
    }
}
