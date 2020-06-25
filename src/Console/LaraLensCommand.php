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

    private const TABLE_STYLES ='default|borderless|compact|symfony-style-guide|box|box-double';
    private const DEFAULT_STYLE='box-double';
    protected $styleTable=self::DEFAULT_STYLE;
    protected $signature = 'laralens:diagnostic
                            {op=overview : What you want to see, overview or allconfigs (overview|allconfigs)}
                            {--table=users : name of the table, default users}
                            {--column-sort=created_at : column name used for sorting}
                            {--show=*all : show (all|config|runtime|connection|database|migration)}
                            {--width-label='.self::DEFAULT_WIDTH.' : width of column for label}
                            {--width-value='.self::DEFAULT_WIDTH.' : width of column for value}
                            {--style='.self::DEFAULT_STYLE.' : style of the output table ('.self::TABLE_STYLES.')}
                            ';

    protected $description = 'Show some application configurations.';

    private const DEFAULT_WIDTH=36;
    protected $widthLabel=self::DEFAULT_WIDTH;
    protected $widthValue=self::DEFAULT_WIDTH;

    public const OPTION_SHOW_NONE= 0b0000000;
    public const OPTION_SHOW_CONFIGS= 0b0000001;
    public const OPTION_SHOW_RUNTIMECONFIGS= 0b00000010;
    public const OPTION_SHOW_CONNECTIONS= 0b00000100;
    public const OPTION_SHOW_DATABASE= 0b00001000;
    public const OPTION_SHOW_MIGRATION= 0b00010000;
    public const OPTION_SHOW_ALL = 0b00011111;

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
        $this->table($headers, $rowsTable,$this->styleTable);
        foreach ($rowsLine as $key =>$line)
        {
            $this->info($line["label"].":");
            $this->line($line["value"]);
            //$this->info($line);
        }
    }

    private function overview($checkTable = "users", $columnSorting = "created_at", $show= self::OPTION_SHOW_ALL)
    {
        $ll = new LaraLens();
        if ($show & self::OPTION_SHOW_CONFIGS)
        {
            $output = $ll->getConfigs();
            $this->print_output(["Config key via config()", "Values"], $output->toArray());
        }
        if ($show & self::OPTION_SHOW_RUNTIMECONFIGS) {
            $output = $ll->getRuntimeConfigs();
            $this->print_output(["Runtime Configs", "Values"], $output->toArray());
        }
        if ($show & self::OPTION_SHOW_RUNTIMECONFIGS) {
            $output = $ll->checkFiles();
            $this->print_output(["Check files", "Values"], $output->toArray());
        }


        if ($show & self::OPTION_SHOW_CONNECTIONS) {
            $output = $ll->getConnections();
            $this->print_output(["Connections", "Values"], $output->toArray());
        }
        if ($show & self::OPTION_SHOW_DATABASE) {
            $output = $ll->getDatabase($checkTable, $columnSorting);
            $this->print_output(["Database", "Values"], $output->toArray());
        }
        if ($show & self::OPTION_SHOW_MIGRATION) {
            $this->call('migrate:status');
        }
    }



    public function handle()
    {
        $op = $this->argument("op");
        $checkTable = $this->option("table");
        $styleTable = $this->option("style");
        if (in_array($styleTable, explode("|", self::TABLE_STYLES))) {
            $this->styleTable = $styleTable;
        } else {
            $this->styleTable = self::DEFAULT_STYLE;
        }
        $columnSorting = $this->option("column-sort");
        $showOptions= $this->option("show");
        if (is_array($showOptions)) {
            if (count($showOptions) >0) {
                $show = self::OPTION_SHOW_NONE;
                $show = (in_array("all", $showOptions)) ? $show | self::OPTION_SHOW_ALL : $show ;
                $show = (in_array("config", $showOptions)) ? $show | self::OPTION_SHOW_CONFIGS : $show ;
                $show = (in_array("runtime", $showOptions)) ? $show | self::OPTION_SHOW_RUNTIMECONFIGS : $show ;
                $show = (in_array("connection", $showOptions)) ? $show | self::OPTION_SHOW_CONNECTIONS : $show ;
                $show = (in_array("database", $showOptions)) ? $show | self::OPTION_SHOW_DATABASE : $show ;
                $show = (in_array("migration", $showOptions)) ? $show | self::OPTION_SHOW_MIGRATION : $show ;
            } else {
                $show = self::OPTION_SHOW_ALL;
            }
        }
        $this->widthLabel= $this->option("width-label");
        $this->widthValue= $this->option("width-value");
        switch ($op) {
            case 'overview':
                $this->overview($checkTable, $columnSorting, $show);
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
