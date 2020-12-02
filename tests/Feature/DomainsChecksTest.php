<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainsChecksTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testDomainCheckStatus()
    {
        $name = "https://www.google.ru/";
        Http::fake([
            $name => Http::response('<html>fake site</html>', 203)
        ]);
        $response = $this->post(route('domains.checks.store', 1));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('domain_checks', ['status_code' => '203']);
    }
}
