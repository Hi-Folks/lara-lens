<?php
namespace HiFolks\LaraLens\Lens\Traits;

use HiFolks\LaraLens\ResultLens;
use Illuminate\Support\Str;

trait RuntimeLens
{

    private function appCaller($results, $functions) {
        $curDir = getcwd();
        foreach ($functions as $function => $label) {
            $value =call_user_func("App::".$function);
            if (Str::length($curDir) > 3) {
                if (Str::startsWith( $value,$curDir )) {
                    $value = ".".Str::after($value, $curDir);
                }
            }
            $results->add(
                $label,
                $value
            );
        }
    }


    public function getRuntimeConfigs()
    {
        $results = new ResultLens();
        $results->add(
            "PHP Version",
            phpversion()
        );
        $results->add(
            "Current Directory",
            getcwd()
        );

        $this->appCaller($results,
            [
                "version"=> "Laravel Version",
                "getLocale"=>"Locale",
                "getNamespace" => "Application namespace",
                "environment"=>"Environment",
                "environmentPath"=>"Environment file directory",
                "environmentFile"=>"Environment file used",
                "environmentFilePath" =>"Full path to the environment file",
                "langPath" =>"Path to the language files",
                "publicPath" =>"Path to the public / web directory",
                "storagePath" => "Storage directory",
                "resourcePath" =>"Resources directory",
                "getCachedServicesPath" => "Path to the cached services.php",
                "getCachedPackagesPath" => "Path to the cached packages.php",
                "getCachedConfigPath" => "Path to the configuration cache",
                "getCachedRoutesPath" => "Path to the routes cache",
                "getCachedEventsPath" => "Path to the events cache file"

            ]

        );

        $results->add(
            "Generated url for / ",
            url("/")
        );
        $results->add(
            "Generated asset url for /test.js ",
            asset("/test.js")
        );

        return $results;
    }
}
