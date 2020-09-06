<?php
namespace HiFolks\LaraLens\Lens\Traits;

use App;
use HiFolks\LaraLens\ResultLens;

trait ConfigLens
{
    public function getConfigsDatabaseFromEnv(ResultLens $results = null)
    {
        if (is_null($results)) {
            $results = new ResultLens();
        }
        $configKeys=[
            "DB_HOST",
            "DB_DATABASE",
            "DB_USERNAME",
            "DB_CONNECTION",
            "DB_PORT"
        ];
        foreach ($configKeys as $key => $value) {
            $results->add(
                ".env ".$value,
                env($value)
            );
        }
        return $results;
    }

    public function getConfigsDatabase(ResultLens $results = null)
    {
        if (is_null($results)) {
            $results = new ResultLens();
        }
        $configKeys=[
            "database.default",
            "database.connections.".config("database.default").".driver",
            "database.connections." . config("database.default") . ".url",
            "database.connections." . config("database.default") . ".host",
            "database.connections." . config("database.default") . ".port",
            "database.connections." . config("database.default") . ".username",
            "database.connections." . config("database.default") . ".database"
        ];
        foreach ($configKeys as $key => $value) {
            $results->add(
                "".$value,
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
        $configKeys=[
            "app.timezone",
            "app.locale",
            "app.name",
            "app.url",
        ];
        foreach ($configKeys as $key => $value) {
            $results->add(
                "".$value,
                config($value)
            );
        }
        $results = $this->getConfigsDatabase($results);
        return $results;
    }
}
