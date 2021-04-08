<?php

namespace HiFolks\LaraLens;

use Illuminate\Support\Facades\Facade;

class LaraLensFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lara-lens';
    }
}
