<?php

namespace HiFolks\LaraLens\Console;

use HiFolks\LaraLens\Lens\LaraLens;
use HiFolks\LaraLens\ResultLens;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class LaraLensCommand extends Command
{
    private const TABLE_STYLES = 'default|borderless|compact|symfony-style-guide|box|box-double';
    private const DEFAULT_STYLE = 'box-double';
    private const DEFAULT_PATH = '';
    protected $styleTable = self::DEFAULT_STYLE;
    protected $signature = 'laralens:diagnostic
                            {op=overview : What you want to see, overview or allconfigs (overview|allconfigs)}
                            {--table=users : name of the table, default users}
                            {--column-sort=created_at : column name used for sorting}
                            {--url-path=' . self::DEFAULT_PATH . ' : default path for checking URL}
                            {--show=* : show (all|config|runtime|connection|database|migration|php-ext|php-ini)}
                            {--width-label=' . self::DEFAULT_WIDTH . ' : width of column for label}
                            {--width-value=' . self::DEFAULT_WIDTH . ' : width of column for value}
                            {--style=' . self::DEFAULT_STYLE . ' : style of the output table (' . self::TABLE_STYLES . ')}
                            {--skip-database : skip database check like connection and migration (if your laravel app doesn\'t need Database)}
                            ';

    protected $description = 'Show some application configurations.';

    private const DEFAULT_WIDTH = 36;
    protected $widthLabel = self::DEFAULT_WIDTH;
    protected $widthValue = self::DEFAULT_WIDTH;

    protected $urlPath = self::DEFAULT_PATH;

    public const OPTION_SHOW_NONE = 0b0000000;
    public const OPTION_SHOW_CONFIGS = 0b0000001;
    public const OPTION_SHOW_RUNTIMECONFIGS = 0b00000010;
    public const OPTION_SHOW_CONNECTIONS = 0b00000100;
    public const OPTION_SHOW_DATABASE = 0b00001000;
    public const OPTION_SHOW_MIGRATION = 0b00010000;
    public const OPTION_SHOW_PHPEXTENSIONS = 0b00100000;
    public const OPTION_SHOW_PHPINIVALUES = 0b01000000;
    public const OPTION_SHOW_ALL = 0b01111111;
    public const OPTION_SHOW_DEFAULT = 0b00011111;

    private function allConfigs()
    {
        $this->info(json_encode(config()->all(), JSON_PRETTY_PRINT));
    }

    private function formatCell($string, $width)
    {
        $retVal = "";
        if (strlen($string) > $width) {
            $retVal = Str::limit($string, $width, '');
        } elseif (strlen($string) < $width) {
            $retVal = str_pad($string, $width);
        } else {
            $retVal =  $string;
        }
        return $retVal;
    }

    private function printOutput(array $headers, array $rows)
    {
        $rowsTable = [];
        $rowsLine = [];
        foreach ($rows as $key => $row) {
            $label = Arr::get($row, "label", "");
            $value = Arr::get($row, "value", "");
            if (is_array($value)) {
                $value = implode(",", $value);
            }
            $isLine = Arr::get($row, "isLine", false);
            $lineType = Arr::get($row, "lineType", ResultLens::LINE_TYPE_DEFAULT);

            if (
                strlen($value) > $this->widthValue ||
                $isLine ||
                $lineType === ResultLens::LINE_TYPE_ERROR ||
                $lineType === ResultLens::LINE_TYPE_WARNING
            ) {
                $rowsLine[] = $row;
            } else {
                $row["label"] = $this->formatCell($label, $this->widthLabel);
                $row["value"] = $this->formatCell($value, $this->widthValue);
                $rowsTable[] = [ $row["label"], $row["value"]   ];
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
        $this->table($headers, $rowsTable, $this->styleTable);
        foreach ($rowsLine as $key => $line) {
            $label = Arr::get($line, "label", "");
            $value = Arr::get($line, "value", "");
            $lineType = Arr::get($line, "lineType", ResultLens::LINE_TYPE_DEFAULT);
            if ($label != "") {
                $this->info($label . ":");
            }
            if ($lineType === ResultLens::LINE_TYPE_ERROR) {
                $this->error($value);
            } elseif ($lineType === ResultLens::LINE_TYPE_WARNING) {
                $this->warn($value);
            } else {
                $this->line($value);
            }
        }
    }

    private function alertGreen($string)
    {
        $length = Str::length(strip_tags($string)) + 12;
        $this->info(str_repeat('*', $length));
        $this->info('*     ' . $string . '     *');
        $this->info(str_repeat('*', $length));
        $this->output->newLine();
    }

    private function printChecks(array $rows)
    {
        if (sizeof($rows) == 0) {
            $this->alertGreen("CHECK: everything looks good");
        } else {
            $this->alert("CHECK: issues found");
        }
        $idx = 0;
        foreach ($rows as $key => $row) {
            $label = Arr::get($row, "label", "");
            $value = Arr::get($row, "value", "");
            $isLine = Arr::get($row, "isLine", false);
            $lineType = Arr::get($row, "lineType", ResultLens::LINE_TYPE_DEFAULT);
            if ($label != "" & ($lineType === ResultLens::LINE_TYPE_ERROR | ResultLens::isMessageLine($lineType))) {
                $idx++;
                $this->warn("--- " . $idx . " ------------------");
                $this->warn("*** " . $label);
            }
            if ($lineType === ResultLens::LINE_TYPE_ERROR) {
                $this->error($value);
            } elseif ($lineType === ResultLens::LINE_TYPE_WARNING) {
                $this->warn($value);
            } elseif ($lineType === ResultLens::LINE_TYPE_INFO) {
                $this->info($value);
            } else {
                $this->comment($value);
            }
        }
    }

    private function overview($checkTable = "users", $columnSorting = "created_at", $show = self::OPTION_SHOW_ALL)
    {
        $ll = new LaraLens();
        if ($show & self::OPTION_SHOW_CONFIGS) {
            $output = $ll->getConfigs();
            $this->printOutput(["Config keys via config()", "Values"], $output->toArray());
        }
        if ($show & self::OPTION_SHOW_RUNTIMECONFIGS) {
            $output = $ll->getRuntimeConfigs();
            $this->printOutput(["Runtime Configs", "Values"], $output->toArray());
            $output = $ll->checkServerRequirements();
            $this->printOutput(["Laravel Requirements", "Values"], $output->toArray());
        }
        if ($show & self::OPTION_SHOW_RUNTIMECONFIGS) {
            $output = $ll->checkFiles();
            $this->printOutput(["Check files", "Values"], $output->toArray());
        }
        if ($show & self::OPTION_SHOW_CONNECTIONS) {
            $output = $ll->getConnections($this->urlPath);
            $this->printOutput(["Connections", "Values"], $output->toArray());
        }
        if ($show & self::OPTION_SHOW_DATABASE) {
            $output = $ll->getDatabase($checkTable, $columnSorting);
            $this->printOutput(["Database", "Values"], $output->toArray());
        }
        if ($show & self::OPTION_SHOW_MIGRATION) {
            try {
                $this->call('migrate:status');
            } catch (\Exception $e) {
                $r = new ResultLens();
                $r->add(
                    "Check migrate status",
                    "Error in check migration"
                );
                $ll->checksBag->addErrorAndHint(
                    "Migration status",
                    $e->getMessage(),
                    "Check the Database configuration"
                );
                $this->printOutput(["Migration" , "result"], $r->toArray());
            }
        }
        if ($show & self::OPTION_SHOW_PHPEXTENSIONS) {
            $output = $ll->getPhpExtensions($checkTable, $columnSorting);
            $this->printOutput(["PHP Extensions"], $output->toArray());
        }
        if ($show & self::OPTION_SHOW_PHPINIVALUES) {
            $output = $ll->getPhpIniValues($checkTable, $columnSorting);
            $this->printOutput(["PHP ini config", "Values"], $output->toArray());
        }
        $this->printChecks($ll->checksBag->toArray());
    }


    public function handle()
    {
        $op = $this->argument("op");
        $checkTable = $this->option("table");
        $styleTable = $this->option("style");
        $show = self::OPTION_SHOW_DEFAULT;
        if (in_array($styleTable, explode("|", self::TABLE_STYLES))) {
            $this->styleTable = $styleTable;
        } else {
            $this->styleTable = self::DEFAULT_STYLE;
        }
        $columnSorting = $this->option("column-sort");
        $showOptions = $this->option("show");


        if (is_array($showOptions)) {
            if (count($showOptions) > 0) {
                $show = self::OPTION_SHOW_NONE;
                if (in_array("all", $showOptions)) {
                    $show = self::OPTION_SHOW_ALL;
                    $skipDatabases = $this->option("skip-database");
                    if ($skipDatabases) {
                        $show = self::OPTION_SHOW_ALL - self::OPTION_SHOW_DATABASE - self::OPTION_SHOW_MIGRATION;
                    }
                } else {
                    $show = (in_array("config", $showOptions)) ? $show | self::OPTION_SHOW_CONFIGS : $show ;
                    $show = (in_array("runtime", $showOptions)) ? $show | self::OPTION_SHOW_RUNTIMECONFIGS : $show ;
                    $show = (in_array("connection", $showOptions)) ? $show | self::OPTION_SHOW_CONNECTIONS : $show ;
                    $show = (in_array("database", $showOptions)) ? $show | self::OPTION_SHOW_DATABASE : $show ;
                    $show = (in_array("migration", $showOptions)) ? $show | self::OPTION_SHOW_MIGRATION : $show ;
                    $show = (in_array("php-ext", $showOptions)) ? $show | self::OPTION_SHOW_PHPEXTENSIONS : $show ;
                    $show = (in_array("php-ini", $showOptions)) ? $show | self::OPTION_SHOW_PHPINIVALUES : $show ;
                }
            } else {
                $show = self::OPTION_SHOW_DEFAULT;
            }
        }

        $this->widthLabel = $this->option("width-label");
        $this->widthValue = $this->option("width-value");

        $this->urlPath = $this->option("url-path");
        if (is_null($this->urlPath)) {
            $this->urlPath = self::DEFAULT_PATH;
        }

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
    }
}
