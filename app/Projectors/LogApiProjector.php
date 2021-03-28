<?php

namespace App\Projectors;

use App\Models\ViewModels\Log;
use App\StorableEvents\LogApiEvent;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class LogApiProjector extends Projector
{
    protected $handlesEvents = [
        LogApiEvent::class => 'onLogApi',
    ];

    public function onStartingEventReplay()
    {
        Log::truncate();
    }

    public function onLogApi(LogApiEvent $event)
    {
        $log = Log::where('url', $event->url)
            ->whereDate('date', $event->date)
            ->first();

        if ($log) {
            $log->update([
                'count' => $log->count + 1
            ]);
        } else {
            Log::create([
                'url' => $event->url,
                'date' => $event->date,
                'count' => 1
            ]);
        }
    }
}
