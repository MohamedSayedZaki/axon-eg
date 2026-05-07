# axon-eg

Sample PHP application for the Axon technical exercise. It is a small [Flight](https://flightphp.com/) service that lists customers from a SQLite database, with request validation, a service layer, dependency injection (League Container), and a simple view. The exercise is meant to show object-oriented design, PSR-style structure, and clean, testable code.

## Requirements

- PHP 8.1+ with the `pdo_sqlite` extension enabled
- [Composer](https://getcomposer.org/)

## Install

From this directory:

```bash
composer install
```

The app expects a SQLite database file at `../sample.db` (one level above the `axon-eg` folder, i.e. next to the `axon-eg` directory in the parent project). Ensure that file exists before running the app.

## Serve the application

From the `axon-eg` directory, use PHP’s built-in web server with the `src` folder as the document root (this is where `index.php` lives):

```bash
php -S localhost:8080 -t src
```

Open [http://localhost:8080/](http://localhost:8080/) in a browser. The home route shows the customer list (with optional query filters as implemented in the app).

## Unit tests

After `composer install`, run:

```bash
composer test
```

This runs PHPUnit as defined in `composer.json`. You can also invoke PHPUnit directly:

```bash
./vendor/bin/phpunit
```

Configuration is in `phpunit.xml`; tests live under `tests/`.
