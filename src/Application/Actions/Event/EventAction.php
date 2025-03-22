<?php

declare(strict_types=1);

namespace App\Application\Actions\Event;

use App\Application\Actions\Action;
use App\Domain\Event\EventRepositoryInterface;
use Psr\Log\LoggerInterface;

abstract class EventAction extends Action
{
    protected EventRepositoryInterface $event;

    public function __construct(LoggerInterface $logger, EventRepositoryInterface $event)
    {
        parent::__construct($logger);
        $this->event = $event;
    }
}