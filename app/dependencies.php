<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Application\Middleware\ApiKeyMiddleware;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Tuupola\Middleware\JwtAuthentication;
use App\Helper\JwtHelper;
use App\Application\Middleware\JwtMiddleware;

use App\Application\Actions\Auth\LoginAction;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        // API KEY
        ApiKeyMiddleware::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            return new ApiKeyMiddleware($settings->get('api')['key']);
        },

        // JWT
        JwtAuthentication::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $jwtSettings = $settings->get('jwt');


            return new JwtAuthentication([
                "secret" => $jwtSettings['secret'],
                "attribute" => $jwtSettings['attribute'],
                "secure" => $jwtSettings['secure'],
                "relaxed" => $jwtSettings['relaxed'],
                "algorithm" => $jwtSettings['algorithm'],
                "error" => function ($response, $arguments) {
                    $data = ['error' => 'Unauthorized', 'message' => $arguments['message']];
                    return $response
                        ->withHeader('Content-Type', 'application/json')
                        ->getBody()->write(json_encode($data));
                }
            ]);
        },

        JwtHelper::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $jwtSettings = $settings->get('jwt');
            $logger = $c->get(LoggerInterface::class);
            return new JwtHelper($jwtSettings['secret'], $logger);
        }, 
          
         JwtMiddleware::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $jwtSettings = $settings->get('jwt');
            return new JwtMiddleware($c, $jwtSettings['secret']);
        },

        // LOGIN
        LoginAction::class => function (ContainerInterface $c) {
            return new LoginAction($c->get(JwtHelper::class));
        },

    ]);
};
