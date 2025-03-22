<?php 

declare(strict_types=1);

namespace App\Domain\Event;
use JsonSerializable;

class Event implements JsonSerializable
{
    private ?int $id;
    private string $name;
    private string $description;
    private string $datetime;
    private string $location;
    private int $capacity;

    public function __construct(?int $id, string $name, string $description, string $datetime, string $location, int $capacity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->datetime = $datetime;
        $this->location = $location;
        $this->capacity = $capacity;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDatetime(): string
    {
        return $this->datetime;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    // Tipo de retorno mudará
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'datetime' => $this->datetime,
            'location' => $this->location,
            'capacity' => $this->capacity,
        ];
    }
}