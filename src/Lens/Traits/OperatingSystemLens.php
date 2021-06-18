<?php

namespace HiFolks\LaraLens\Lens\Traits;

use HiFolks\LaraLens\ResultLens;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait OperatingSystemLens
{


    private function getUnameValues($results)
    {
        $modes =  [
            "s" => "Operating System",
            "n" => "Hostname",
            "r" => "Release name",
            "v" => "Version info",
            "m" => "Machine Name",
            "a" => "Full infos"
        ];

        foreach ($modes as $key => $title) {
            $results->add(
                $title,
                php_uname($key)
            );
        }
    }

    public function getOsConfigs()
    {
        $results = new ResultLens();
        $results->add(
            "PHP script owner's UID",
            getmyuid()
        );
        $results->add(
            "Current User",
            get_current_user()
        );
        $this->getUnameValues($results);
        return $results;
    }
}
