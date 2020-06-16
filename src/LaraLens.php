<?php

namespace HiFolks\LaraLens;
use App;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Str;


class LaraLens
{
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
            "Connected to URL",
            $url
        );

        try {
            $response = Http::get($url);
            $results->add(
                "Connection HTTP Status",
                $response->status()
            );

        } catch (\Exception $e){
            $results->add(
                "Connection HTTP Status",
                "Connection Error: " . $e->getMessage()
            );
        }

        return $results;
    }


    public function getTablesListMysql()
    {
        $tables = DB::select('SHOW TABLES');
        $stringTables = "";
        foreach ($tables as $table) {
            foreach ($table as $key => $value) {
                $stringTables = $stringTables . $value . ";";
            }
        }
        return $stringTables;
    }
    public function getTablesListSqlite()
    {
        $tables = DB::table('sqlite_master')
            ->select('name')
            ->where('type', 'table')
            ->orderBy('name')
            ->pluck('name')->toArray();
        $stringTables = implode(",", $tables);
        return $stringTables;

    }



    public function getDatabase($checkTable="users", $columnSorting = "created_at")
    {
        $results = new ResultLens();
        $results->add(
            "Database type",
            config("database.default")
        );
        $grammar= DB::connection()->getQueryGrammar();
        $results->add(
            "Query Grammar",
            Str::afterLast(get_class($grammar), '\\')
        );
        $connectionType= DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $results->add(
            "Database connection type",
            $connectionType
        );
        $stringTables="";
        switch ($connectionType) {
            case 'mysql':
                $stringTables= $this->getTablesListMysql();
                break;
            case 'sqlite':
                $stringTables = $this->getTablesListSqlite();
                break;

            default:
                $stringTables = "<<skipped ". $connectionType.">>";
                break;
        }
        $results->add(
            "Tables",
            $stringTables
        );

        $checkountMessage= "";
        try {
            $checkcount = DB::table($checkTable)
                ->select(DB::raw('*'))
                ->count();
        } catch (\Exception $e){
            $checkcount = 0;
            $checkountMessage= " - error with ".$checkTable." table";
        }

        $results->add(
            "Query Table",
            $checkTable
        );
        $results->add(
            "Number of rows",
            $checkcount . $checkountMessage
        );
        if ($checkcount > 0) {
            try {

                $latest = DB::table($checkTable)->latest($columnSorting)->first();
                $results->add(
                    "LAST row in table",
                    json_encode($latest)
                );
            } catch (QueryException $e) {
                $results->add(
                    "LAST row in table",
                    "Failed query, table <".$checkTable."> column <".$columnSorting.">"
                );
            }

        }
        return $results;
    }

    public function getRuntimeConfigs()
    {
        $results = new ResultLens();
        $results->add(
            "App::getLocale()",
            App::getLocale()
        );
        $results->add(
            "App::environment()",
            App::environment()
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
                "".$value,
                config($value)
            );
        }
        return $results;
    }
}
