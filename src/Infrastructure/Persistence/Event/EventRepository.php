<?php

declare(strict_types=1);

namespace App\Infrastructure\Event;

use App\Domain\Event\Event;
use App\Domain\Event\EventNotFoundException;

use App\Domain\Event\EventRepositoryInterface;

class EventRepository implements EventRepositoryInterface
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function save(Event $data): ?int {
        $stmt = $this->db->prepare("
            INSERT INTO events (
                name, description, datetime, location, capacity
            ) VALUES (
                :name, :description, :datetime, :location, :capacity
            )");

        $name = $data->getName();
        $description = $data->getDescription();
        $datetime = $data->getDatetime();
        $location = $data->getLocation();
        $capacity = $data->getCapacity();

        $insert = $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':datetime' => $datetime,
            ':location' => $location,
            ':capacity' => $capacity
        ]);

        if($insert){
            return (int) $this->db->lastInsertId();
        } else {
            return null;
        }


    }
}