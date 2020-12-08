<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainsChecksTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testDomainCheckStatus()
    {
        $domain = DB::table('domains')->first('*');
        $name = $domain->name;
        $body = file_get_contents(__DIR__ . '/fixtures/test_response.html');
        $status = 200;
        Http::fake([$name => Http::response($body, $status)]);
        $response = $this->post(route('domains.checks.store', $domain->id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas(
            'domain_checks',
            [
                'domain_id' => $domain->id,
                'status_code' => $status,
                'h1' => 'h1 tag',
                'keywords' => 'test, keywords',
                'description' => 'test Description'
            ]
        );
    }
}
