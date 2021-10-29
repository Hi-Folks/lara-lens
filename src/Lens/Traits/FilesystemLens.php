<?php

namespace HiFolks\LaraLens\Lens\Traits;

use HiFolks\LaraLens\ResultLens;
use Illuminate\Support\Facades\App;

trait FilesystemLens
{
    use BaseTraits;

    protected function links()
    {
        return config("filesystems.links") ??
            [public_path('storage') => storage_path('app/public')];
        //return $this->laravel['config']['filesystems.links'] ??
    }
    public function checkFiles(): \HiFolks\LaraLens\ResultLens
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
            $langArray = false;
        }
        $languages = "";
        if ($langArray) {
            $languages = implode(",", array_diff($langArray, array('..', '.', 'vendor')));
        } else {
            $languages = "No language found";
            $this->checksBag->addWarningAndHint(
                "List Languages directory",
                "No languages found in " . App::langPath(),
                "If your app needs translations, please fill " . App::langPath()
            );
        }
        $results->add(
            "List Languages directory",
            $languages
        );

        foreach ($this->links() as $link => $dir) {
            if (! file_exists($link)) {
                $this->checksBag->addWarningAndHint(
                    "Check storage link",
                    $this->stripPrefixDir($link) . " it doesn't exist.",
                    "Check config/filesystem.php 'links' parameter, and execute 'php artisan storage:link'"
                );
            }
            if (! file_exists($dir)) {
                $this->checksBag->addWarningAndHint(
                    "Check storage target link",
                    $this->stripPrefixDir($dir) . " it doesn't exist.",
                    "Create directory target (for storage link) : " . $dir
                );
            }

            $results->add(
                "Storage links:",
                ""
            );
            $results->add(
                $this->stripPrefixDir($link),
                $this->stripPrefixDir($dir)
            );
        }

        return $results;
    }
}
