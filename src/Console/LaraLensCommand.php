<?php

namespace HiFolks\LaraLens\Console;

use HiFolks\LaraLens\Lens\LaraLens;
use HiFolks\LaraLens\Lens\Traits\TermOutput;
use HiFolks\LaraLens\ResultLens;
use Illuminate\Console\Command;

class LaraLensCommand extends Command
{
    use TermOutput;

    private const TABLE_STYLES = 'default|borderless|compact|symfony-style-guide|box|box-double';
    private const DEFAULT_STYLE = 'box-double';
    private const DEFAULT_PATH = '';
    protected $styleTable = self::DEFAULT_STYLE;
    protected $signature = 'laralens:diagnostic
                            {op=overview : What you want to see, overview or allconfigs (overview|allconfigs)}
                            {--table=users : name of the table, default users}
                            {--column-sort=created_at : column name used for sorting}
                            {--url-path=' . self::DEFAULT_PATH . ' : default path for checking URL}
                            {--show=* : show (all|config|runtime|connection|database|migration|php-ext|php-ini|os)}
                            {--width-label=' . self::DEFAULT_WIDTH . ' : width of column for label}
                            {--width-value=' . self::DEFAULT_WIDTH . ' : width of column for value}
                            {--large : use 120 columns for the output}
                            {--style=' . self::DEFAULT_STYLE . ' : style for output table (' . self::TABLE_STYLES . ')}
                            {--skip-database : skip database check (if your laravel app doesn\'t need Database)}
                            {--a|all : verbose output (--show=all)}
                            ';

    protected $description = 'Show some application configurations.';

    private const DEFAULT_WIDTH = 36;
    private const DEFAULT_LARGE_WIDTH = 77;
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
    public const OPTION_SHOW_OS = 0b10000000;
    public const OPTION_SHOW_ALL = 0b11111111;
    public const OPTION_SHOW_DEFAULT = 0b00011111;

    private function allConfigs(): void
    {
        $this->info(json_encode(config()->all(), JSON_PRETTY_PRINT));
    }

    private function overview($checkTable = "users", $columnSorting = "created_at", $show = self::OPTION_SHOW_ALL): void
    {
        $ll = new LaraLens();
        if ($show & self::OPTION_SHOW_CONFIGS) {
            $output = $ll->getConfigs();
            $this->printOutputTerm("Config keys via config()", $output->toArray());
        }
        if ($show & self::OPTION_SHOW_RUNTIMECONFIGS) {
            $output = $ll->getRuntimeConfigs();
            $this->printOutputTerm("Runtime Configs", $output->toArray());
            $output = $ll->checkServerRequirements();
            $this->printOutputTerm("Laravel Requirements", $output->toArray());
        }
        if ($show & self::OPTION_SHOW_RUNTIMECONFIGS) {
            $output = $ll->checkFiles();
            $this->printOutputTerm("Check files", $output->toArray());
        }
        if ($show & self::OPTION_SHOW_CONNECTIONS) {
            $output = $ll->getConnections($this->urlPath);
            $this->printOutputTerm("Connections", $output->toArray());
        }
        if ($show & self::OPTION_SHOW_DATABASE) {
            $output = $ll->getDatabase($checkTable, $columnSorting);
            $this->printOutputTerm("Database", $output->toArray());
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
                $this->printOutputTerm("Migration", $r->toArray());
            }
        }
        if ($show & self::OPTION_SHOW_PHPEXTENSIONS) {
            $output = $ll->getPhpExtensions();
            $this->printOutputTerm("PHP Extensions", $output->toArray());
        }
        if ($show & self::OPTION_SHOW_PHPINIVALUES) {
            $output = $ll->getPhpIniValues();
            $this->printOutputTerm("PHP ini config", $output->toArray());
        }
        if ($show & self::OPTION_SHOW_OS) {
            $output = $ll->getOsConfigs();
            $this->printOutputTerm("Operating System", $output->toArray());
        }
        $this->printChecksTerm($ll->checksBag->toArray());
    }


    public function handle(): void
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
        $showOptions = $this->option("all") ? ['all'] : $this->option("show");


        if (is_array($showOptions)) {
            if (count($showOptions) > 0) {
                $show = self::OPTION_SHOW_NONE;
                if (in_array("all", $showOptions)) {
                    $show = self::OPTION_SHOW_ALL;
                } else {
                    $show = (in_array("config", $showOptions)) ? $show | self::OPTION_SHOW_CONFIGS : $show ;
                    $show = (in_array("runtime", $showOptions)) ? $show | self::OPTION_SHOW_RUNTIMECONFIGS : $show ;
                    $show = (in_array("connection", $showOptions)) ? $show | self::OPTION_SHOW_CONNECTIONS : $show ;
                    $show = (in_array("database", $showOptions)) ? $show | self::OPTION_SHOW_DATABASE : $show ;
                    $show = (in_array("migration", $showOptions)) ? $show | self::OPTION_SHOW_MIGRATION : $show ;
                    $show = (in_array("php-ext", $showOptions)) ? $show | self::OPTION_SHOW_PHPEXTENSIONS : $show ;
                    $show = (in_array("php-ini", $showOptions)) ? $show | self::OPTION_SHOW_PHPINIVALUES : $show ;
                    $show = (in_array("os", $showOptions)) ? $show | self::OPTION_SHOW_OS : $show ;
                }
            } else {
                $show = self::OPTION_SHOW_DEFAULT;
            }
        }
        $skipDatabases = $this->option("skip-database");
        if ($skipDatabases) {
            $show = $show - self::OPTION_SHOW_DATABASE - self::OPTION_SHOW_MIGRATION;
        }

        $this->widthLabel = $this->option("width-label");
        $this->widthValue = $this->option("width-value");
        if ($this->option("large")) {
            $this->widthValue = self::DEFAULT_LARGE_WIDTH;
        }

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
