<?php

namespace HiFolks\LaraLens;
use App;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;


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
            "Connect to URL",
            $url
        );

        try {
            $response = Http::get($url);
            $results->add(
                "Connection HTTP Status",
                $response->status()
            );

        } catch (\Exception $e){
            $results->addErrorAndHint(
                "Connection HTTP Status",
                "Connection Error: " . $e->getMessage(),
                "Check this URL: " . $url . " in .env file APP_URL"
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

        try {
            $dbconnection = DB::connection();
        } catch (\Exception $e) {
            $results->addErrorAndHint(
                "Error Database connection",
                "- ".$e->getCode()." - ". $e->getMessage(),
                "Check out your .env file for these parameters: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD",
            );
            $results = $this->getConfigsDatabase($results);
            $results = $this->getConfigsDatabaseFromEnv($results);
            return $results;
        }



        $results->add(
            "Database default",
            config("database.default")
        );
        $connectionName= $dbconnection->getName();
        $results->add(
            "Connection name",
            $connectionName
        );
        $grammar= $dbconnection->getQueryGrammar();
        $results->add(
            "Query Grammar",
            Str::afterLast(get_class($grammar), '\\')
        );
        $driverName= $dbconnection->getDriverName();
        $results->add(
            "Driver name",
            $driverName
        );
        $databaseName= $dbconnection->getDatabaseName();
        $results->add(
            "Database name",
            $databaseName
        );
        $tablePrefix= $dbconnection->getTablePrefix();
        $results->add(
            "Table prefix",
            $tablePrefix
        );



        //$serverVersion= $dbconnection->getConfig('server_version');

        try {
            $serverVersion = $dbconnection->getPDO()->getAttribute(\PDO::ATTR_SERVER_VERSION);
            $results->add(
                "Server version",
                $serverVersion
            );
        } catch (\PDOException $e) {
            $results->addErrorAndHint(
                "Error DB",
                $e->getMessage(),
                "Check out your .env file for these parameters: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD",
            );
            $results = $this->getConfigsDatabase($results);
            $results = $this->getConfigsDatabaseFromEnv($results);
            return $results;
        }


        $connectionType= $dbconnection->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);
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
                //"App::".$function."()",
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
        $results->add(
            "Check .env exists",
            self::printBool(file_exists(App::environmentFilePath()))
        );

        $results->add(
            "Check Languages directory",
            self::printBool(is_dir(App::langPath()))
        );
        $langArray = scandir(App::langPath());
        $languages = "";
        if ($langArray) {
            //$s = app('translator');
            //$langNamespaces = $s->getLoader()->namespaces();

            $languages = implode(",", array_diff($langArray, array('..', '.', 'vendor')));
            //if (is_array($langNamespaces && sizeof($langNamespaces)>0)) {
            //    $languages = $languages."; Namespaces:".implode("," , $langNamespaces);
            //}
        } else {
            $languages = "No language found";
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


    public function getConfigsDatabaseFromEnv(ResultLens $results = null) {
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

    public function getConfigsDatabase(ResultLens $results = null) {
        if (is_null($results)) {
            $results = new ResultLens();
        }

        $configKeys=[
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
