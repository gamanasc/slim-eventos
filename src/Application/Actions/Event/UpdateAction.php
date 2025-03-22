<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use App\Domain\Event\Event;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Event\InvalidEventDataException;


class UpdateAction extends EventAction
{
    protected function action(): Response
    {
        $eventId = (int) $this->resolveArg('id');
        $data = $this->request->getParsedBody();
        
        if($eventId == 0){
            throw new InvalidEventDataException("Invalid event id");
        }
        
        if(is_null($data)){
            throw new InvalidEventDataException();
        }

        $event = new Event(
            NULL,
            $data['name'],
            $data['description'],
            $data['datetime'],
            $data['location'],
            (int) $data['capacity']
        );
        $result = $this->event->update($eventId, $event);

        if($result){
            $this->logger->info("Event with id `{$eventId}` was succesfully updated");
            return $this->respondWithData("Event with id `{$eventId}` was succesfully updated");
        }else{
            return $this->respondWithData("Unable to update event {$eventId}");
        }
    }
}