<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainsTest extends TestCase
{
    use RefreshDatabase;

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
