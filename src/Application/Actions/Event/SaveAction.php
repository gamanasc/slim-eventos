<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use App\Domain\Event\Event;
use Psr\Http\Message\ResponseInterface as Response;

class SaveAction extends EventAction
{
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $event = new Event(
            NULL,
            $data['name'],
            $data['description'],
            $data['datetime'],
            $data['location'],
            $data['capacity']
        );
        $eventId = $this->event->save($event);

        $this->logger->info("Event created with ID: `${eventId}`");

        return $this->respondWithData("Event created with ID: $eventId");
    }
}