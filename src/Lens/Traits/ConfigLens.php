<?php

namespace HiFolks\LaraLens\Lens\Traits;

use HiFolks\LaraLens\ResultLens;

trait ConfigLens
{
    public function getConfigsDatabaseFromEnv(ResultLens $results = null): \HiFolks\LaraLens\ResultLens
    {
        if (is_null($results)) {
            $results = new ResultLens();
        }
        $configKeys = [
            "DB_HOST",
            "DB_DATABASE",
            "DB_USERNAME",
            "DB_CONNECTION",
            "DB_PORT"
        ];
        foreach ($configKeys as $key => $value) {
            $results->add(
                ".env " . $value,
                env($value)
            );
        }
        return $results;
    }

    public function checkDebugEnv(ResultLens $results = null): \HiFolks\LaraLens\ResultLens
    {
        if (is_null($results)) {
            $results  = new ResultLens();
        }
        $debug = config("app.debug");
        $env = config("app.env");
        $results->add(
            "ENV",
            $env
        );
        $results->add(
            "DEBUG",
            $debug
        );
        if ($debug && $env === "production") {
            $this->checksBag->addWarningAndHint(
                "Check config ENV and DEBUG",
                "You have DEBUG mode in Production.",
                "Change you APP_DEBUG env parameter to false for Production environments"
            );
        }
        return $results;
    }

    public function getConfigsDatabase(ResultLens $results = null): \HiFolks\LaraLens\ResultLens
    {
        if (is_null($results)) {
            $results = new ResultLens();
        }
        $configKeys = [
            "database.default",
            "database.connections." . config("database.default") . ".driver",
            "database.connections." . config("database.default") . ".url",
            "database.connections." . config("database.default") . ".host",
            "database.connections." . config("database.default") . ".port",
            "database.connections." . config("database.default") . ".username",
            "database.connections." . config("database.default") . ".database"
        ];
        foreach ($configKeys as $key => $value) {
            $results->add(
                "" . $value,
                config($value)
            );
        }
        return $results;
    }

    public function getConfigs()
    {
        $results = new ResultLens();
        $results->add(
            "Running diagnostic",
            date('Y-m-d H:i:s')
        );
        $configKeys = [
            "app.timezone",
            "app.locale",
            "app.name",
            "app.url",
        ];
        foreach ($configKeys as $key => $value) {
            $results->add(
                "" . $value,
                config($value)
            );
        }
        $results = $this->checkDebugEnv($results);
        $results = $this->getConfigsDatabase($results);
        return $results;
    }
}
