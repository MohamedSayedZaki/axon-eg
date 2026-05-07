<?php

declare(strict_types=1);

use flight\util\Collection;
use Mohamedsayedzaki\AxonEg\Requests\CustomerRequest;
use PHPUnit\Framework\TestCase;

final class CustomerRequestTest extends TestCase
{
    public function testValidatedUsesDefaultsForMissingKeys(): void
    {
        $subject = new CustomerRequest();
        $result = $subject->validated(new Collection([]));

        self::assertSame([
            'country' => '',
            'validity' => '',
            'page' => 1,
        ], $result);
    }

    public function testValidatedPassesThroughCountryAndValidity(): void
    {
        $subject = new CustomerRequest();
        $result = $subject->validated(new Collection([
            'country' => '237',
            'validity' => '1',
            'page' => '3',
        ]));

        self::assertSame([
            'country' => '237',
            'validity' => '1',
            'page' => 3,
        ], $result);
    }

    public function testValidatedCoercesNonNumericPageToOne(): void
    {
        $subject = new CustomerRequest();
        $result = $subject->validated(new Collection([
            'country' => '',
            'validity' => '',
            'page' => 'abc',
        ]));

        self::assertSame(1, $result['page']);
    }

    public function testValidatedFloorsPageBelowOneToOne(): void
    {
        $subject = new CustomerRequest();
        $result = $subject->validated(new Collection([
            'page' => '0',
        ]));

        self::assertSame(1, $result['page']);
    }
}
