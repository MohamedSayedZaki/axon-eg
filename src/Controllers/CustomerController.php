<?php

declare(strict_types=1);

namespace Mohamedsayedzaki\AxonEg\Controllers;

use Flight;
use Mohamedsayedzaki\AxonEg\Services\CustomerService;

class CustomerController
{
    public function __construct(private readonly CustomerService $customerService)
    {
    }

    public function getAllCustomers(): void
    {
        $request = $this->validateRequest();

        $customers = $this->customerService->getAllCustomers($request);

        Flight::view()->render('customer.php', [
            'customers' => $customers,
            'country' => $request['country'],
            'validity' => $request['validity'],
            'page' => (int) $request['page'],
        ]);
    }

    private function validateRequest(): array
    {
        $country = Flight::request()->query['country'] ?? '';
        $validity = Flight::request()->query['validity'] ?? '';
        $pageRaw = Flight::request()->query['page'] ?? 1;
        $page = is_numeric($pageRaw) ? max(1, (int) $pageRaw) : 1;

        return [
            'country' => $country,
            'validity' => $validity,
            'page' => $page,
        ];
    }
}
