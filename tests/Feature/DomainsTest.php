<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function testShow()
    {
        $id = $this->faker->numberBetween(1, 3);
        $responce = $this->get(route('domains.show', $id));
        $responce->assertOk();

        $this->assertDatabaseHas('domains', ['id' => $id]);
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
        $response->assertSessionHasErrors();
        $response->assertRedirect();

        $this->assertDatabaseMissing('domains', $data);
    }

    public function domainNamesProvider()
    {
        return [
            ['http://rambler.ru'],
            ['https://google.com'],
            ['https://ya.ru']
        ];
    }

    public function invalidDomainNamesProvider()
    {
        return [
            ['ramblerru'],
            ['googlecom'],
            ['yaru']
        ];
    }
}
