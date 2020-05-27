<?php

namespace HiFolks\LaraLens\Console;

use HiFolks\LaraLens\LaraLens;
use Illuminate\Console\Command;
use HiFolks\LaraLens\LaraLensServiceProvider;

class LaraLensCommand extends Command
{
    protected $signature = 'laralens:diagnostic
                            {op=overview : What you want to see, overview or allconfigs}
                            {--table=users : name of the table, default users}
                            {--column-sort=created_at : column name used for sorting}
                            {--skip-connection : skip the connection testing}
                            ';

    protected $description = 'Show some application configuration.';

    private function allConfigs()
    {
        $this->info(json_encode(config()->all(), JSON_PRETTY_PRINT));
    }

    private function overview($checkTable = "users", $columnSorting = "created_at")
    {
        $ll = new LaraLens();
        $output = $ll->getConfigs();
        $this->table(["Configs", "Values"], $output->toArray());
        $output = $ll->getRuntimeConfigs();
        $this->table(["Runtime Configs", "Values"], $output->toArray());


        $output = $ll->getConnections();
        $this->table(["Connections", "Values"], $output->toArray());
        $output = $ll->getDatabase($checkTable, $columnSorting);
        $this->table(["Database", "Values"], $output->toArray());

        $this->call('migrate:status');

    }
    public function handle()
    {
        $op = $this->argument("op");
        $checkTable = $this->option("table");
        $columnSorting = $this->option("column-sort");
        $skipConnection= $this->option("skip-connection");


        switch ($op) {
            case 'overview':
                $this->overview($checkTable, $columnSorting);
                break;
            case 'allconfigs':
                $this->allConfigs();
                break;

            default:
                $this->info("What you mean? try with 'php artisan laralens:diagnostic --help'");
                break;
        }
        //$this->info("Start");

    }
}
