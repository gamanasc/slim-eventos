<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use App\Domain\Event\Event;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends EventAction
{
    protected function action(): Response
    {
        $eventId = (int) $this->resolveArg('id');
        $result = $this->event->delete($eventId);

        if($result){
            $this->logger->info("Event with ID: `{$eventId}` deleted.");
            return $this->respondWithData("Event with ID: $eventId deleted.");
        }else{
            $this->logger->info("Event with ID: `{$eventId}` not found.");
            return $this->respondWithData("Event with ID: $eventId not found.", 404);
        }
    }
}