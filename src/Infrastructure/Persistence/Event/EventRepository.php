<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Event;

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

    /**
    * Retrieves all events from the database.
    *
    * @return Event[] An array of Event objects.
    */
    public function findAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM events");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
    * Retrieves a single event from the database based on its ID.
    *
    * @return Event|null The Event object if found, or null if no event exists with the given ID.
    */
    public function find(int $id): ?Event
    {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE id = :id");
        $stmt->execute([
            ':id' => $id
        ]);
        $result = $stmt->fetch();

        if ($result) {
            return new Event(
                $result['id'],
                $result['name'],
                $result['description'],
                $result['datetime'],
                $result['location'],
                (int) $result['capacity']
            );
        }

        throw new EventNotFoundException("Event with ID {$id} not found");
    }
    
    /**
    * Saves an event to the database.
    *
    * @param Event $data The event data to be saved.
    * @return int|null The ID of the saved event, or null on failure.
    */
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

    public function update(int $id, Event $data): bool {
        $stmt = $this->db->prepare("
            UPDATE events SET
                name = :name,
                description = :description,
                datetime = :datetime,
                location = :location,
                capacity = :capacity
            WHERE 
                id = :id");
        
        $name = $data->getName();
        $description = $data->getDescription();
        $datetime = $data->getDatetime();
        $location = $data->getLocation();
        $capacity = $data->getCapacity();

        $update = $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':datetime' => $datetime,
            ':location' => $location,
            ':capacity' => $capacity,
            ':id'       => $id
        ]);

        return $update;
        
    }
}