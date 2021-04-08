<?php

namespace HiFolks\LaraLens\Lens\Traits;

use Illuminate\Support\Str;

trait BaseTraits
{
    protected function stripPrefixDir($value)
    {
        //$curDir = getcwd();
        $curDir = base_path();
        if (Str::length($curDir) > 3) {
            if (Str::startsWith($value, $curDir)) {
                $value = "." . Str::after($value, $curDir);
            }
        }
        return $value;
    }
}
