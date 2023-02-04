<?php

namespace HiFolks\LaraLens\Lens\Traits;

use HiFolks\LaraLens\ResultLens;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait RuntimeLens
{
    private function appCaller($results, $functions): void
    {
        $curDir = getcwd();
        foreach ($functions as $function => $label) {
            $value = call_user_func("App::" . $function);
            if (Str::length($curDir) > 3) {
                if (Str::startsWith($value, $curDir)) {
                    $value = "." . Str::after($value, $curDir);
                }
            }
            $results->add(
                $label,
                $value
            );
        }
    }

    private function getIniValues($results): void
    {
        $labels = [
            "post_max_size",
            "upload_max_filesize",
        ];
        foreach ($labels as $label) {
            $results->add(
                $label,
                ini_get($label)
            );
        }
    }

    public function getRuntimeConfigs(): \HiFolks\LaraLens\ResultLens
    {
        $results = new ResultLens();
        $results->add(
            "PHP Version",
            phpversion()
        );
        $this->getIniValues($results);
        $results->add(
            "Current Directory",
            getcwd()
        );
        $this->appCaller(
            $results,
            [
                "version" => "Laravel Version",
                "getLocale" => "Locale",
                "getNamespace" => "Application namespace",
                "environment" => "Environment",
                "environmentPath" => "Environment file directory",
                "environmentFile" => "Environment file used",
                "environmentFilePath" => "Full path to the environment file",
                "langPath" => "Path to the language files",
                "publicPath" => "Path to the public / web directory",
                "storagePath" => "Storage directory",
                "resourcePath" => "Resources directory",
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

    public function checkServerRequirements(): \HiFolks\LaraLens\ResultLens
    {
        $results = new ResultLens();

        $phpVersion = phpversion();
        $laravelVersion = app()->version();
        $laravelMajorVersion = Arr::get(explode('.', $laravelVersion), 0, "8");

        $phpExtensionRequirements = [
            "6" => [
                "phpversion" => "7.2.0",
                "extensions" => [
                    "bcmath",
                    "ctype",
                    "fileinfo",
                    "json",
                    "mbstring",
                    "openssl",
                    "pdo",
                    "tokenizer",
                    "xml"
                ]
            ],
            "7" => [
                "phpversion" => "7.2.5",
                "extensions" => [
                    "bcmath",
                    "ctype",
                    "fileinfo",
                    "json",
                    "mbstring",
                    "openssl",
                    "pdo",
                    "tokenizer",
                    "xml"
                ]
            ],
            "8" => [
                "phpversion" => "7.3.0",
                "extensions" => [
                    "bcmath",
                    "ctype",
                    "fileinfo",
                    "json",
                    "mbstring",
                    "openssl",
                    "pdo",
                    "tokenizer",
                    "xml"
                ]
            ],
            "9" => [
                "phpversion" => "8.0.2",
                "extensions" => [
                    "bcmath",
                    "ctype",
                    "fileinfo",
                    "json",
                    "mbstring",
                    "openssl",
                    "pdo",
                    "tokenizer",
                    "xml"
                ]
            ],
            "10" => [
                "phpversion" => "8.1.0",
                "extensions" => [
                    "bcmath",
                    "ctype",
                    "curl",
                    "dom",
                    "fileinfo",
                    "json",
                    "mbstring",
                    "openssl",
                    "pcre",
                    "pdo",
                    "tokenizer",
                    "xml"
                ]
            ]

        ];
        if (! key_exists($laravelMajorVersion, $phpExtensionRequirements)) {
            $laravelMajorVersion = "8";
        }
        $phpVersionRequired = $phpExtensionRequirements[$laravelMajorVersion]["phpversion"];
        $results->add(
            "Laravel version",
            $laravelVersion . " ( " . $laravelMajorVersion . " )"
        );
        $results->add(
            "PHP version",
            $phpVersion
        );
        $results->add(
            "PHP version required (min)",
            $phpVersionRequired
        );

        $helpInstall = [
            "bcmath" => "BCMath Arbitrary Precision Mathematics: https://www.php.net/manual/en/bc.setup.php",
            "ctype" => "Character type checking: https://www.php.net/manual/en/book.ctype",
            "fileinfo" => "File Information: https://www.php.net/manual/en/book.fileinfo",
            "json" => "JavaScript Object Notation: https://www.php.net/manual/en/book.json",
            "mbstring" => "Multibyte string: https://www.php.net/manual/en/book.mbstring.php",
            "openssl" => "OpenSSL: https://www.php.net/manual/en/book.openssl.php",
            "pdo" => "PHP Data Objects: https://www.php.net/manual/en/book.pdo.php",
            "tokenizer" => "Tokenizer: https://www.php.net/manual/en/book.tokenizer.php",
            "xml" => "XML Parser: https://www.php.net/manual/en/book.xml.php"
        ];
        $modulesOk = [];
        $modulesNotok = [];
        foreach ($phpExtensionRequirements[$laravelMajorVersion]["extensions"] as $p) {
            if (extension_loaded($p)) {
                $modulesOk[] = $p;
            } else {
                $modulesNotok[] = $p;
            }
        }
        //*** CHECK PHP VERSION
        if (version_compare($phpVersion, $phpVersionRequired) < 0) {
            $this->checksBag->addWarningAndHint(
                "PHP (" . $phpVersion . ") version check",
                "PHP version required: " . $phpVersionRequired . ", you have: " . $phpVersion,
                "You need to install PHP version: " . $phpVersionRequired
            );
        }
        $results->add(
            "PHP (" . $phpVersion . ") version check",
            "PHP version required: " . $phpVersionRequired . ", you have: " . $phpVersion
        );
        $results->add(
            "PHP extensions installed",
            implode(",", $modulesOk)
        );
        if (count($modulesNotok) > 0) {
            $stringHint = "Please install these modules :" . PHP_EOL;
            foreach ($modulesNotok as $pko) {
                if (key_exists($pko, $helpInstall)) {
                    $stringHint = $pko . " : " . $helpInstall[$pko] . PHP_EOL;
                } else {
                    $stringHint = $pko . PHP_EOL;
                }
            }
            $this->checksBag->addWarningAndHint(
                "PHP extensions missing",
                "Some PHP Extensions are missing",
                $stringHint
            );
        } else {
            $results->add(
                "PHP extension installed",
                "Looks good for Laravel " . $laravelMajorVersion
            );
        }
        return $results;
    }

    public function getPhpExtensions(): \HiFolks\LaraLens\ResultLens
    {
        $results = new ResultLens();
        foreach (get_loaded_extensions() as $name) {
            $results->add(
                $name,
                ""
            );
        }
        return $results;
    }

    public function getPhpIniValues(): \HiFolks\LaraLens\ResultLens
    {
        $results = new ResultLens();
        foreach (ini_get_all() as $name => $row) {
            $results->add(
                $name,
                $row['local_value']
            );
        }
        return $results;
    }
}
