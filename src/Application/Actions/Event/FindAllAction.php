<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use App\Domain\Event\Event;
use Psr\Http\Message\ResponseInterface as Response;

class FindAllAction extends EventAction
{
    protected function action(): Response
    {
        $events = $this->event->findAll();
        $this->logger->info("Event list was viewed.");
        return $this->respondWithData($events);
    }
}