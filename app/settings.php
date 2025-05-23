<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'api' => [
                    'key' => $_ENV['API_KEY'],
                ],
                'db'=> function(){
                    $host = $_ENV['DB_HOST'];
                    $dbname = $_ENV['DB_NAME'];
                    $user = $_ENV['DB_USER'];
                    $pass = $_ENV['DB_PASS'];
 
                    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    return $pdo;
                },
                'jwt' => [
                    'secret' => 'secret_value_123456',
                    'attribute' => 'token',
                    'secure' => false, // Set to true in production
                    'relaxed' => ['localhost', 'your-domain.com'],
                    'algorithm' => ['HS256'],
                ],
            ]);
        }
    ]);
};
