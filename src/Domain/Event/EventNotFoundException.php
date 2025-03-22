<?php
namespace App\Domain\Event;

class EventNotFoundException extends \Exception
{
    public function __construct(string $message = 'Event not found', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}