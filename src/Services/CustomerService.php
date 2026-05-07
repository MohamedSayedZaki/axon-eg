<?php

declare(strict_types=1);

namespace Mohamedsayedzaki\AxonEg\Services;

use flight\database\SimplePdo;
use PDO;

class CustomerService
{
    public function __construct(private readonly SimplePdo $db)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAllCustomers(array $request): array
    {
        if ($request['country'] === '' || ! ctype_digit($request['country'])) {
            return [];
        }

        $dialPattern = '^\s*\(' . preg_quote($request['country'], '#') . '\)';

        $sql = $request['validity'] === '1'
            ? 'SELECT * FROM customer WHERE phone REGEXP ?'
            : 'SELECT * FROM customer WHERE NOT (phone REGEXP ?)';

        $page = max(1, (int) $request['page']);
        $sql .= ' LIMIT 5 OFFSET ' . (($page - 1) * 5);

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dialPattern]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
