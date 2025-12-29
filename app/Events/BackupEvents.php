<?php

namespace App\Events;

use App\Models\Backup;

class BackupEvents
{
    public function created(Backup $backup): void
    {
        if (!$backup->canFireEvents('created')) {
            return;
        }

        Backup::deleteOldFiles();
    }
}
