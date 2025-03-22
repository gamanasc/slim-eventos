<?php 

declare(strict_types=1);

namespace App\Domain\Event;

interface EventRepositoryInterface
{
    // public function findAll(): array;
    // public function find(int $id): ?Event;
    public function save(Event $event): ?int;
    // public function update(Event $event): bool;
    // public function delete(int $id): bool;
}