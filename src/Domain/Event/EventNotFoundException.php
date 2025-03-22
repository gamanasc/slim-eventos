<?php
namespace App\Domain\Event;

class EventNotFoundException extends \Exception
{
    protected $message = 'Event not found';
}