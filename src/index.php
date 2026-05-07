<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use flight\database\SimplePdo;
use League\Container\Container;
use Mohamedsayedzaki\AxonEg\Controllers\CustomerController;
use Mohamedsayedzaki\AxonEg\Services\CustomerService;

$container = new Container();

$container->addShared(SimplePdo::class, static function (): SimplePdo {
    $pdo = new SimplePdo(
        'sqlite:' . __DIR__ . '/../../sample.db',
        '',
        '',
        [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );

    $pdo->sqliteCreateFunction(
        'regexp',
        static function (?string $pattern, ?string $value): int {
            if ($pattern === null || $pattern === '' || $value === null) {
                return 0;
            }

            return preg_match('#' . $pattern . '#u', $value) === 1 ? 1 : 0;
        }
    );

    return $pdo;
});

$container->add(CustomerService::class)->addArgument(SimplePdo::class);
$container->add(CustomerController::class)->addArgument(CustomerService::class);

Flight::registerContainerHandler($container);

Flight::set('flight.views.path', __DIR__ . '/views');

Flight::route('/js/customer.js', static function (): void {
    $file = __DIR__ . '/js/customer.js';
    if (! is_readable($file)) {
        Flight::halt(404);

        return;
    }
    Flight::response()->header('Content-Type', 'application/javascript; charset=utf-8');
    readfile($file);
});

Flight::route('/css/customer.css', static function (): void {
    $file = __DIR__ . '/css/customer.css';
    if (! is_readable($file)) {
        Flight::halt(404);

        return;
    }
    Flight::response()->header('Content-Type', 'text/css; charset=utf-8');
    readfile($file);
});

Flight::route('/', [CustomerController::class, 'getAllCustomers']);

Flight::start();
