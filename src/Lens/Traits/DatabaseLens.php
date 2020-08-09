<?php

namespace HiFolks\LaraLens\Lens\Traits;
use App;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use HiFolks\LaraLens\ResultLens;


trait DatabaseLens
{

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

    /**
     * Try to establish a db connection.
     * If it fails, return FALSE and fill checksBag.
     * @return false|\Illuminate\Database\ConnectionInterface
     */
    public function dbConnection() {
        $dbconnection = false;
        try {
            $dbconnection = DB::connection();
        } catch (\Exception $e) {
            $dbconnection = false;
            $this->checksBag->addErrorAndHint(
                "Error Database connection",
                "- ".$e->getCode()." - ". $e->getMessage(),
                "Check out your .env file for these parameters: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD",
            );
        }
        return $dbconnection;
    }

    public function getDatabaseConnectionInfos(ConnectionInterface $dbconnection, ResultLens $results, $checkTable, $columnSorting)
    {
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

        $pdo = null;
        $pdoIsOk = false;
        try {
            $pdo = $dbconnection->getPDO();
            $pdoIsOk = true;
        } catch (\PDOException $e ) {
            $this->checksBag->addWarningAndHint(
                "Access to PDO (database)",
                $e->getMessage(),
                "Check .env DB_*"
            );
        }

        if ( ! $pdoIsOk) {
            if ($driverName === "mongodb") {
                $this->checksBag->addInfoAndHint(
                    "Connection and PDO driver",
                    "It is ok! Because you are using ".$driverName. ", and it doesn't support PDO driver.",
                    ""
                );
            } else {
                $this->checksBag->addWarningAndHint(
                    "Connection and PDO driver",
                    "Your DB doesn't support PDO driver (". $driverName. ").",
                    ""
                );
            }

        } else {
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
                $results->addErrorAndHint(
                    "Table Error",
                    "Failed query, table <".$checkTable."> ",
                    "Make sure that table <".$checkTable."> exists, available tables : ".(($stringTables == "") ? "Not tables found": $stringTables)
                );
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
                    $results->addErrorAndHint(
                        "Table Error",
                        "Failed query, table <".$checkTable."> column <".$columnSorting.">",
                        "Make sure that table <".$checkTable."> column <".$columnSorting."> exists"
                    );
                }
            }
        }


    }

    public function getDatabase($checkTable="users", $columnSorting = "created_at")
    {

        $results = new ResultLens();

        $dbconnection = $this->dbConnection();


        $results->add(
            "Database default",
            config("database.default")
        );
        if ($dbconnection) {
            $this->getDatabaseConnectionInfos($dbconnection, $results,  $checkTable, $columnSorting);
        }





        return $results;
    }
}
