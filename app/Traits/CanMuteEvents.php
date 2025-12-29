<?php

namespace App\Traits;

trait CanMuteEvents
{
    private array $muteEvents = [];

    public function canFireEvents(string $event): bool
    {
        return !in_array($event, $this->muteEvents);
    }

    protected function muteEvents($events): void
    {
        if (!is_array($events)) {
            $events = [$events];
        }

        $this->muteEvents = array_merge($this->muteEvents, $events);
    }
}
