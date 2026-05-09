<?php

declare(strict_types=1);

namespace Mohamedsayedzaki\AxonEg\Services;

use Mohamedsayedzaki\AxonEg\Repositories\CustomerRepository;

class CustomerService
{
    public function __construct(private readonly CustomerRepository $customerRepository) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAllCustomers(array $request): array
    {
        if ($request['country'] === '' || ! ctype_digit($request['country'])) {
            return [];
        }

        return $this->customerRepository->getAllCustomers($request);
    }
}
