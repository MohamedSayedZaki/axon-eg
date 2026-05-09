<?php

declare(strict_types=1);

namespace Mohamedsayedzaki\AxonEg\Repositories;

use flight\database\SimplePdo;
use PDO;

class CustomerRepository
{
    private const ITEMS_PER_PAGE = 5;

    public function __construct(private readonly SimplePdo $db) {}

    public function getAllCustomers(array $request): array
    {
        $dialPattern = '^\s*\('.preg_quote($request['country'], '#').'\)';
        $page = $this->normalizePageNumber($request['page']);
        $sql = $this->buildQueryWithFilters($request['validity'] ?? '0');
        $sql = $this->applyPagination($sql, $page);

        return $this->fetchResults($sql, $dialPattern);
    }

    private function normalizePageNumber(int $page): int
    {
        return max(1, (int) $page);
    }

    private function buildQueryWithFilters(string $validity): string
    {
        $whereClause = $validity === '1'
            ? 'phone REGEXP ?'
            : 'NOT (phone REGEXP ?)';

        return "SELECT * FROM customer WHERE {$whereClause}";
    }

    private function applyPagination(string $sql, int $page): string
    {
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;

        return $sql.' LIMIT '.self::ITEMS_PER_PAGE." OFFSET {$offset}";
    }

    private function fetchResults(string $sql, string $dialPattern): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dialPattern]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
