<?php

namespace HiFolks\LaraLens\Tests;

use HiFolks\LaraLens\LaraLensServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [LaraLensServiceProvider::class];
    }
}
