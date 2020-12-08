<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DomainsTest extends TestCase
{
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
        $domain = DB::table('domains')->find($id)->name;
        $responce = $this->get(route('domains.show', $id));
        $responce->assertOk();
        $responce->assertSee($domain);
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
