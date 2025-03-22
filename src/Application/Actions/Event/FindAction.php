<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use App\Domain\Event\Event;
use Psr\Http\Message\ResponseInterface as Response;

class FindAction extends EventAction
{
    protected function action(): Response
    {
        $eventId = (int) $this->resolveArg('id');
        $event = $this->event->find($eventId);
        $this->logger->info("Event with id `{$eventId}` was viewed.");
        return $this->respondWithData($event);
    }
}