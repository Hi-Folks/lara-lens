<?php

namespace HiFolks\LaraLens;
use App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class LaraLens
{
    // Build your next great package.
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
        $response = Http::get($url);
        $results->add(
            "Connected to URL",
            $url
        );
        $results->add(
            "Connection HTTP Status",
            $response->status()
        );
        return $results;
    }

    public function getDatabase($checkTable="users")
    {
        $results = new ResultLens();
        $results->add(
            "Config",
            config("database.default")
        );
        $checkcount = DB::table($checkTable)
            ->select(DB::raw('*'))
            ->count();

        $results->add(
            "Query Table",
            $checkTable
        );
        $results->add(
            "Number of rows",
            $checkcount
        );
        if ($checkcount > 0) {
            $latest = DB::table($checkTable)->latest()->first();
            $results->add(
                "LAST row in table",
                json_encode($latest)
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
            "database.default",
            "database.connections.".config("database.default").".driver",
            "database.connections." . config("database.default") . ".url",
            "database.connections." . config("database.default") . ".host",
            "database.connections." . config("database.default") . ".username",
            "database.connections." . config("database.default") . ".database"

        ];
        foreach ($configKeys as $key => $value) {
            $results->add(
                "Config key ".$value,
                config($value)
            );
        }
        $results->add(
            "App::getLocale()",
            App::getLocale()
        );
        return $results;
    }
}
