<?php
namespace App\Domain\Event;

class InvalidEventDataException extends \Exception
{
    public function __construct(string $message = 'Invalid event data provided', int $code = 400)
    {
        parent::__construct($message, $code);
    }
}