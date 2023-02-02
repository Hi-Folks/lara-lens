<?php

namespace HiFolks\LaraLens\Lens;

use HiFolks\LaraLens\Lens\Traits\ConfigLens;
use HiFolks\LaraLens\Lens\Traits\DatabaseLens;
use HiFolks\LaraLens\Lens\Traits\FilesystemLens;
use HiFolks\LaraLens\Lens\Traits\HttpConnectionLens;
use HiFolks\LaraLens\Lens\Traits\OperatingSystemLens;
use HiFolks\LaraLens\Lens\Traits\RuntimeLens;
use HiFolks\LaraLens\ResultLens;

class LaraLens
{
    use DatabaseLens;
    use ConfigLens;
    use HttpConnectionLens;
    use RuntimeLens;
    use FilesystemLens;
    use OperatingSystemLens;

    /**
     * @var ResultLens
     */
    public $checksBag;

    public function __construct()
    {
        $this->checksBag = new ResultLens();
    }

    /**
     * @return ResultLens
     */
    public function getCredits()
    {
        $results = new ResultLens();
        $results->add(
            "App",
            "powered by LaraLens"
        );


        return $results;
    }


    /**
     * @param  bool $b
     * @return string
     */
    public static function printBool(bool $b)
    {
        return $b ? "Yes" : "No";
    }
}
