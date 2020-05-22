<?php

namespace HiFolks\LaraLens\Console;

use HiFolks\LaraLens\LaraLens;
use Illuminate\Console\Command;
use HiFolks\LaraLens\LaraLensServiceProvider;

class LaraLensCommand extends Command
{
    protected $signature = 'laralens:diagnostic
                            {op=overview : What you want to see, overview or allconfigs}';

    protected $description = 'Show some application configruation.';

    private function allConfigs()
    {
        $this->info(json_encode(config()->all(), JSON_PRETTY_PRINT));
    }

    private function overview()
    {
        $ll = new LaraLens();
        $output = $ll->getConfigs();
        $this->table(["Configs", "Values"], $output->toArray());
        $output = $ll->getRuntimeConfigs();
        $this->table(["Runtime Configs", "Values"], $output->toArray());


        $output = $ll->getConnections();
        $this->table(["Connections", "Values"], $output->toArray());
        $output = $ll->getDatabase("users");
        $this->table(["Database", "Values"], $output->toArray());

        $this->call('migrate:status');

    }
    public function handle()
    {
        $op = $this->argument("op");
        switch ($op) {
            case 'overview':
                $this->overview();
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
