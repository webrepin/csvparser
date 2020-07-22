<?php
namespace app\CsvParser\Providers;


use App\CsvParser\Console\Commands\ParseCsvCommand;
use Carbon\Laravel\ServiceProvider;

/**
 * Class ParserServiceProvider
 * @package app\CsvParser\Providers
 */
class ParserServiceProvider extends ServiceProvider
{
    /**
     * Register commands to module CsvParser
     */
    protected function registerCommands(): void
    {
        $this->commands([
            ParseCsvCommand::class
        ]);
    }

    public function boot()
    {
        $this->registerCommands();
    }
}