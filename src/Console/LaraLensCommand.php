<?php

namespace HiFolks\LaraLens\Console;

use HiFolks\LaraLens\LaraLens;
use Illuminate\Console\Command;
use HiFolks\LaraLens\LaraLensServiceProvider;

class LaraLensCommand extends Command
{
    protected $signature = 'laralens:diagnostic';

    protected $description = 'Show some application configuation.';

    public function handle()
    {
        $this->info("Start");
        $ll = new LaraLens();
        $output = $ll->getConfigs();
        $this->table(["Configs", "Values"], $output->toArray());
        $output = $ll->getRuntimeConfigs();
        $this->table(["Runtime Configs", "Values"], $output->toArray());


        $output = $ll->getConnections();
        $this->table(["Connections", "Values"], $output->toArray());
        $output = $ll->getDatabase("users");
        $this->table(["Database", "Values"], $output->toArray());

    }
}
