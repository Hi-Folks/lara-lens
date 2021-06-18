<?php

namespace HiFolks\LaraLens\Tests;

use HiFolks\LaraLens\Lens\LaraLens;
use Illuminate\Support\Facades\Config;
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
        $this->assertCount(14, $a->toArray());
        $arrayChecks = $l->checksBag->toArray();

        $this->assertCount(0, $arrayChecks, "test check config length 0");

        $l = new LaraLens();
        $a = $l->getConfigs();
        config(['app.debug' => true]);
        config(['app.env' => "production"]);
        $l = new LaraLens();
        $a = $l->getConfigs();
        $arrayChecks = $l->checksBag->toArray();

        // 2 =  1 for the warning , 1 for the hint
        $this->assertCount(2, $arrayChecks);



    }
    /** @test */
    public function test_runtimeconfig_array()
    {
        $l = new LaraLens();
        $a = $l->getRuntimeConfigs();
        $this->assertIsArray($a->toArray());
        $this->assertCount(22, $a->toArray());
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

    /** @test */
    public function test_php_ext_array()
    {
        $l = new LaraLens();
        $a = $l->getPhpExtensions();
        $this->assertIsArray($a->toArray());
        $this->assertGreaterThan(0, count($a->toArray()));
    }

    /** @test */
    public function test_php_ini_array()
    {
        $l = new LaraLens();
        $a = $l->getPhpIniValues();
        $this->assertIsArray($a->toArray());
        $this->assertGreaterThan(0, count($a->toArray()));
    }

    /** @test */
    public function test_os_config_array()
    {
        $l = new LaraLens();
        $a = $l->getOsConfigs();
        $this->assertIsArray($a->toArray());
        $this->assertLessThanOrEqual(8, count($a->toArray()));
    }

}
