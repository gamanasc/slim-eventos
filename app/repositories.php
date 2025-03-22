<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;
use App\Domain\Event\EventRepositoryInterface;
use App\Infrastructure\Persistence\Event\EventRepository;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
    ]);

    $containerBuilder->addDefinitions([
        EventRepositoryInterface::class => function ($container) {
            $db = $container->get('db');
            return new EventRepository($db);
        },
    ]);
};
