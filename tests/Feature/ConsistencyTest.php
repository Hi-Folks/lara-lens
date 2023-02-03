<?php

use Illuminate\Support\Facades\Http;

test('config_array', function () {
    expect(laralens())->getConfigs()
    ->toArray()
    ->toBeArray()
    ->toHaveCount(14)
    ->checksBag->toArray()
    ->toBeArray()
    ->toBeEmpty(); //test check config length 0


    config(['app.debug' => true]);
    config(['app.env' => "production"]);

    expect(laralens())->getConfigs()
    ->toArray()
    ->toBeArray()
    ->toHaveCount(14)
    ->checksBag->toArray()
    ->toBeArray()
    ->toHaveCount(2);  // 2 =  1 for the warning , 1 for the hint
});

it('can retrieve runtimeconfig as array of 22 items')
    ->expect(fn () => laralens())
    ->getRuntimeConfigs()->toArray()
    ->toBeArray()
    ->toHaveCount(22);

it('can retrieve databases as array with 8-13 items')
    ->expect(fn () => laralens())
    ->getDatabase()->toArray()
    ->toBeArray()
    ->toHaveCountBetween(8, 13);

test('facade', function () {
    $this->assertIsObject($this->app["lara-lens"], "Test object facade");
});

test('Credits returned as non-empty array')
    ->expect(fn () => laralens())
    ->getCredits()->toArray()
    ->toBeArray()
    ->not()->toBeEmpty();

it('can retrieve PHP extensions as array')
    ->expect(fn () => laralens())
    ->getPhpExtensions()->toArray()
    ->toBeArray()
    ->not()->toBeEmpty();

it('can retrieve PHP ini as array')
    ->expect(fn () => laralens())
    ->getPhpIniValues()->toArray()
    ->toBeArray()
    ->not()->toBeEmpty();

it('can retrieve OS config as array')
    ->expect(fn () => laralens())
    ->getOsConfigs()->toArray()
    ->toBeArray()
    ->toHaveCountBetween(0, 8);

test('connection_array', function () {
    Http::fake();

    expect(fn () => laralens())
        ->getConnections()->toArray()
        ->toBeArray()
        ->toHaveCount(3);
})->skip();
