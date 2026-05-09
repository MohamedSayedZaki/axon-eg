<?php

declare(strict_types=1);

use Mohamedsayedzaki\AxonEg\Controllers\CustomerController;
use Mohamedsayedzaki\AxonEg\Services\CustomerService;
use PHPUnit\Framework\TestCase;

final class CustomerControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_GET = [];
        Flight::app()->init();
        Flight::set('flight.views.path', dirname(__DIR__, 2).'/src/Resources/views');
    }

    protected function tearDown(): void
    {
        $_GET = [];
        parent::tearDown();
    }

    public function test_get_all_customers_passes_validated_query_to_service_and_renders_customers(): void
    {
        $_GET = [
            'country' => '237',
            'validity' => '1',
            'page' => '2',
        ];

        $service = $this->createMock(CustomerService::class);
        $service->expects(self::once())
            ->method('getAllCustomers')
            ->with([
                'country' => '237',
                'validity' => '1',
                'page' => 2,
            ])
            ->willReturn([
                [
                    'country' => 'Cameroon',
                    'phone' => '(237) 699209038',
                ],
            ]);

        $controller = new CustomerController($service);

        ob_start();
        $controller->getAllCustomers();
        $html = ob_get_clean();

        self::assertStringContainsString('Cameroon', $html);
        self::assertStringContainsString('699209038', $html);
        self::assertStringContainsString('(237)', $html);
    }

    public function test_get_all_customers_renders_empty_table_when_service_returns_no_rows(): void
    {
        $_GET = ['country' => '256', 'validity' => '2', 'page' => '1'];

        $service = $this->createStub(CustomerService::class);
        $service->method('getAllCustomers')->willReturn([]);

        $controller = new CustomerController($service);

        ob_start();
        $controller->getAllCustomers();
        $html = ob_get_clean();

        self::assertStringContainsString('<tbody>', $html);
        self::assertStringNotContainsString('nok', $html);
    }
}
