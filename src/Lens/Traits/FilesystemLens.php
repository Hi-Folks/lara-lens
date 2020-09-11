<?php

namespace HiFolks\LaraLens\Lens\Traits;

use HiFolks\LaraLens\ResultLens;
use Illuminate\Support\Facades\App;


trait FilesystemLens
{
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
        return $results;
    }
}
