<?php

namespace HiFolks\LaraLens\Console;

use HiFolks\LaraLens\LaraLens;
use Illuminate\Console\Command;
use HiFolks\LaraLens\LaraLensServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\TableStyle;

class LaraLensCommand extends Command
{
    protected $signature = 'laralens:diagnostic
                            {op=overview : What you want to see, overview or allconfigs}
                            {--table=users : name of the table, default users}
                            {--column-sort=created_at : column name used for sorting}
                            {--skip-connection : skip the connection testing}
                            {--width-label='.self::DEFAULT_WIDTH.' : width of column for label}
                            {--width-value='.self::DEFAULT_WIDTH.' : width of column for value}
                            ';

    protected $description = 'Show some application configurations.';

    private const DEFAULT_WIDTH=36;
    protected $widthLabel=self::DEFAULT_WIDTH;
    protected $widthValue=self::DEFAULT_WIDTH;

    private function allConfigs()
    {
        $this->info(json_encode(config()->all(), JSON_PRETTY_PRINT));
    }

    private function formatCell($string, $width)
    {
        $retVal = "";
        if (strlen($string)> $width)
        {
            $retVal = Str::limit($string, $width, '');
        } else if (strlen($string)< $width)
        {
            $retVal = str_pad($string, $width);
        } else {
            $retVal =  $string;
        }
        return $retVal;
    }

    private function print_output(array $headers, array $rows)
    {
        /*
            'default' => new TableStyle(),
            'borderless' => $borderless,
            'compact' => $compact,
            'symfony-style-guide' => $styleGuide,
            'box' => $box,
            'box-double' => $boxDouble,
        */
        $rowsTable = [];
        $rowsLine = [];
        foreach ($rows as $key => $row)
        {
            if (strlen($row["value"]) > $this->widthValue) {
                $rowsLine[] = $row;
            } else {
                $label = Arr::get($row, "label", "");
                $value = Arr::get($row, "value", "");
                $row["label"] = $this->formatCell($label, $this->widthLabel);
                $row["value"] = $this->formatCell($value, $this->widthValue);
                $rowsTable[] = $row;
            }
        }
        /*
         * table style:
         * 'default'
         * 'borderless'
         * 'compact'
         * 'symfony-style-guide'
         * 'box'
         * 'box-double'
         */
        $this->table($headers, $rowsTable,"box-double");
        foreach ($rowsLine as $key =>$line)
        {
            $this->info($line["label"].":");
            $this->line($line["value"]);
            //$this->info($line);
        }
    }

    private function overview($checkTable = "users", $columnSorting = "created_at")
    {
        $ll = new LaraLens();
        $output = $ll->getConfigs();
        $this->print_output(["Config key via config()", "Values"], $output->toArray());
        $output = $ll->getRuntimeConfigs();
        $this->print_output(["Runtime Configs", "Values"], $output->toArray());
        $output = $ll->getConnections();
        $this->print_output(["Connections", "Values"], $output->toArray());
        $output = $ll->getDatabase($checkTable, $columnSorting);
        $this->print_output(["Database", "Values"], $output->toArray());
        $this->call('migrate:status');
    }

    public function handle()
    {
        $op = $this->argument("op");
        $checkTable = $this->option("table");
        $columnSorting = $this->option("column-sort");
        $skipConnection= $this->option("skip-connection");
        $this->widthLabel= $this->option("width-label");
        $this->widthValue= $this->option("width-value");
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
