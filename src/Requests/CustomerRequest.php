<?php

declare(strict_types=1);

namespace Mohamedsayedzaki\AxonEg\Requests;

use flight\util\Collection;

class CustomerRequest
{
    public function validated(Collection $request): array
    {
        $country = $request['country'] ?? '';
        $validity = $request['validity'] ?? '';
        $pageRaw = $request['page'] ?? 1;
        $page = is_numeric($pageRaw) ? max(1, (int) $pageRaw) : 1;

        return [
            'country' => $country,
            'validity' => $validity,
            'page' => $page,
        ];
    }
}
