<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainsTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
        $this->seed();
    }

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    /**
    * @dataProvider domainNamesProvider
    */
    public function testStore($domainName)
    {
        $data = ['name' => $domainName];
        $response = $this->post(route('domains.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domains', $data);
    }

    /**
    * @dataProvider invalidDomainNamesProvider
    */
    public function testStoreInvalidDomain($invalidDomainName)
    {
        $data = ['name' => $invalidDomainName];
        $response = $this->post(route('domains.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseMissing('domains', $data);
    }

    public function testDomainCheckStatus()
    {
        $name = "https://www.google.ru/";
        Http::fake([
            $name => Http::response('<html>fake site</html>', 203)
        ]);
        $response = $this->post(route('domains.check', 1));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('domain_checks', ['status_code' => '203']);
    }

    public function domainNamesProvider()
    {
        return [
            ['http://rambler.ru'],
            ['https://google.com'],
            ['https://ya.ru']
        ] ;
    }

    public function invalidDomainNamesProvider()
    {
        return [
            ['ramblerru'],
            ['googlecom'],
            ['yaru']
        ] ;
    }
}
