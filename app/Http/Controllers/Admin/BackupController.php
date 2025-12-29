<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\BackupRestoreJob;
use App\Models\Backup;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Imtigger\LaravelJobStatus\JobStatus;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function index(): View
    {
        $backups = Backup::latest()->get();
        return view('admin.pages.backups.index', compact('backups'));
    }

    public function make(): JsonResponse
    {
        $backup = Backup::makeBackup();
        return !$backup->hasErrors()
            ? response()->json(['message' => 'Backup created!'])
            : response()->json($backup->getErrors(), 422);
    }

    public function downloadBackup(Backup $backup): BinaryFileResponse
    {
        return response()->download($backup->getFullPath());
    }

    public function remove(Request $request): JsonResponse
    {
        $id = $request->get('id');

        $backup = Backup::findOrFail($id);
        File::delete($backup->getFullPath());
        $backup->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function restore(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $job = new BackupRestoreJob($request->get('id'));
        $job->onQueue('backups');
        $this->dispatch($job);

        return response()->json(['job' => $job->getJobStatusId()]);
    }

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:102400',
        ]);

        $file = $request->file('file');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $existing = Backup::where('file', $fileName)->first();
        if ($existing) {
            return response()->json(['message' => "Backup is exist! Please restore it"], 500);
        }

        $relativePath = 'backups/' . $file->getClientOriginalName();
        $destination = base_path($relativePath);

        $folder = dirname($destination);

        if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
            return response()->json(['message' => "Couldn't create directory $folder"], 500);
        }

        $result = File::copy($file->getRealPath(), $destination);

        if (!$result) {
            return response()->json(['message' => "Couldn't save file"], 500);
        }

        $backup = Backup::create([
            'file' => $fileName,
            'ext' => '.' . $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ]);

        return response()->json([
            'success' => true,
            'attachment' => $backup->id,
            'url' => asset($relativePath),
            'redirect' => true,
        ]);
    }

    public function getJobStatus(Request $request): JsonResponse
    {
        $jobId = $request->get('job');
        $jobStatus = JobStatus::find($jobId);

        return response()->json($jobStatus->toArray());
    }
}

