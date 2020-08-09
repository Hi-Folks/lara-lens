<?php

namespace HiFolks\LaraLens\Lens;
use App;
use HiFolks\LaraLens\Lens\Traits\ConfigLens;
use HiFolks\LaraLens\Lens\Traits\DatabaseLens;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Str;

use HiFolks\LaraLens\ResultLens;


class LaraLens
{
    use DatabaseLens;
    use ConfigLens;

    public $checksBag;

    public function __construct()
    {
        $this->checksBag = new ResultLens();
    }

    public function getCredits()
    {
        $results = new ResultLens();
        $results->add(
            "App",
            "powered by LaraLens"
        );
        return $results;
    }

    public function getConnections()
    {
        $results = new ResultLens();
        $url = config("app.url");
        $results->add(
            "Connect to URL",
            $url
        );

        try {
            $response = Http::get($url);
            $results->add(
                "Connection HTTP Status",
                $response->status()
            );
            if ($response->failed()) {
                $this->checksBag->addWarningAndHint(
                    "Connection HTTP Status",
                    "Connection response not 20x, status code: " . $response->status(),
                    "Check this URL: " . $url . " in .env file APP_URL"
                );

            }


        } catch (\Exception $e){
            $results->add(
                "Connection HTTP Status",
                "Error connection"
            );
            $this->checksBag->addErrorAndHint(
                "Connection HTTP Status",
                "Connection Error: " . $e->getMessage(),
                "Check this URL: " . $url . " in .env file APP_URL"
            );
        }
        return $results;
    }


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

    public static function printBool(bool $b) {
        return $b ? "Yes": "No";
    }

    public function checkFiles()
    {
        $results = new ResultLens();
        $envExists = file_exists(App::environmentFilePath());
        if ($envExists) {
            $results->add(
                "Check .env exists",
                self::printBool($envExists)
            );
        } else {
            $results->addWarningAndHint(
                "Check .env exists",
                ".env not exists",
                "Create .env file"
            );
        }
        $results->add(
            "Check Languages directory",
            self::printBool(is_dir(App::langPath()))
        );
        try {
            $langArray = scandir(App::langPath());
        } catch (\Exception $e) {
            $langArray= false;
        }
        $languages = "";
        if ($langArray) {
            $languages = implode(",", array_diff($langArray, array('..', '.', 'vendor')));
        } else {
            $languages = "No language found";
            $this->checksBag->addWarningAndHint(
                "List Languages directory",
                "No languages found in " . App::langPath(),
                "If your app needs translations, please fill ". App::langPath()
            );
        }
        $results->add(
            "List Languages directory",
            $languages
        );
        return $results;
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
