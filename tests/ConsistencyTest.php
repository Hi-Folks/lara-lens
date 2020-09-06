<?php

namespace HiFolks\LaraLens\Tests;

use HiFolks\LaraLens\Lens\LaraLens;
use Orchestra\Testbench\TestCase;
use HiFolks\LaraLens\LaraLensServiceProvider;
use Illuminate\Support\Facades\Http;

class ConsistencyTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaraLensServiceProvider::class];
    }

    /** @test */
    public function test_config_array()
    {
        $l = new LaraLens();
        $a = $l->getConfigs();
        $this->assertIsArray($a->toArray());
        $this->assertCount(12, $a->toArray());

    }
    /** @test */
    public function test_runtimeconfig_array()
    {
        $l = new LaraLens();
        $a = $l->getRuntimeConfigs();
        $this->assertIsArray($a->toArray());
        $this->assertCount(20, $a->toArray());
    }
    /** @test */
    public function test_database_array()
    {
        $l = new LaraLens();
        $a = $l->getDatabase();
        $this->assertIsArray($a->toArray());
        $this->assertGreaterThanOrEqual(8, count($a->toArray()));
        $this->assertLessThanOrEqual(13, count($a->toArray()));
    }


    /** @test */
    public function test_facade()
    {
        $this->assertIsObject($this->app["lara-lens"], "Test object facade");

    }

    /*
    public function test_connection_array()
    {
        Http::fake();
        $l = new LaraLens();
        $a = $l->getConnections();
        $this->assertIsArray($a->toArray());
        $this->assertEquals(3, count($a->toArray()));
    }
    */

    /** @test */
    public function test_credit_array()
    {
        $l = new LaraLens();
        $a = $l->getCredits();
        $this->assertIsArray($a->toArray());
        $this->assertGreaterThan(0, count($a->toArray()));
    }

}
