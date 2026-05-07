<?php

declare(strict_types=1);

use flight\database\SimplePdo;
use Mohamedsayedzaki\AxonEg\Services\CustomerService;
use PHPUnit\Framework\TestCase;

final class CustomerServiceTest extends TestCase
{
    private function createMemoryDb(): SimplePdo
    {
        $pdo = new SimplePdo(
            'sqlite::memory:',
            '',
            '',
            [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
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

        $pdo->exec(
            'CREATE TABLE customer (
                id INTEGER PRIMARY KEY,
                country TEXT NOT NULL,
                phone TEXT NOT NULL
            )'
        );

        return $pdo;
    }

    public function testGetAllCustomersReturnsEmptyWhenCountryIsEmpty(): void
    {
        $service = new CustomerService($this->createMemoryDb());

        self::assertSame([], $service->getAllCustomers([
            'country' => '',
            'validity' => '1',
            'page' => 1,
        ]));
    }

    public function testGetAllCustomersReturnsEmptyWhenCountryIsNotNumeric(): void
    {
        $service = new CustomerService($this->createMemoryDb());

        self::assertSame([], $service->getAllCustomers([
            'country' => '23a',
            'validity' => '1',
            'page' => 1,
        ]));
    }

    public function testGetAllCustomersFiltersValidPhonesWhenValidityIsOne(): void
    {
        $db = $this->createMemoryDb();
        $db->exec("INSERT INTO customer (id, country, phone) VALUES
            (1, 'Cameroon', '(237) match-one'),
            (2, 'Cameroon', '(251) no-match')");

        $service = new CustomerService($db);
        $rows = $service->getAllCustomers([
            'country' => '237',
            'validity' => '1',
            'page' => 1,
        ]);

        self::assertCount(1, $rows);
        self::assertSame('(237) match-one', $rows[0]['phone']);
    }

    public function testGetAllCustomersFiltersInvalidPhonesWhenValidityIsNotOne(): void
    {
        $db = $this->createMemoryDb();
        $db->exec("INSERT INTO customer (id, country, phone) VALUES
            (1, 'Cameroon', '(237) match-one'),
            (2, 'Cameroon', '(251) no-match')");

        $service = new CustomerService($db);
        $rows = $service->getAllCustomers([
            'country' => '237',
            'validity' => '2',
            'page' => 1,
        ]);

        self::assertCount(1, $rows);
        self::assertSame('(251) no-match', $rows[0]['phone']);
    }

    public function testGetAllCustomersPaginatesFivePerPage(): void
    {
        $db = $this->createMemoryDb();
        $stmt = $db->prepare('INSERT INTO customer (id, country, phone) VALUES (?, ?, ?)');
        for ($i = 1; $i <= 6; $i++) {
            $stmt->execute([$i, 'Cameroon', '(237) num-' . $i]);
        }

        $service = new CustomerService($db);

        $page1 = $service->getAllCustomers([
            'country' => '237',
            'validity' => '1',
            'page' => 1,
        ]);
        $page2 = $service->getAllCustomers([
            'country' => '237',
            'validity' => '1',
            'page' => 2,
        ]);

        self::assertCount(5, $page1);
        self::assertCount(1, $page2);
    }
}
