<?php

declare(strict_types=1);

namespace Mohamedsayedzaki\AxonEg\Controllers;

use Flight;
use Mohamedsayedzaki\AxonEg\Requests\CustomerRequest;
use Mohamedsayedzaki\AxonEg\Services\CustomerService;

class CustomerController
{
    public function __construct(private readonly CustomerService $customerService) {}

    public function getAllCustomers(): void
    {
        $request = (new CustomerRequest)->validated(Flight::request()->query);

        $customers = $this->customerService->getAllCustomers($request);

        Flight::view()->render('customer.php', [
            'customers' => $customers,
            'country' => $request['country'],
            'validity' => $request['validity'],
            'page' => (int) $request['page'],
        ]);
    }
}
