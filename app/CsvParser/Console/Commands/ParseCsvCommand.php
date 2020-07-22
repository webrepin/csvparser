<?php
namespace App\CsvParser\Console\Commands;

use App\CsvParser\Services\SyncDataServiceInterface;
use Illuminate\Console\Command;

/**
 * The command is synchronize CVS data file with DataBase storage data
 * throw the service
 *
 * Class ParseCsvCommand
 * @package App\CsvParser\Console\Commands
 */
class ParseCsvCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'parser:use {file}';

    /**
     * @var string
     */
    protected $description = 'Upload user data file with CSV rows to `storage/app/`';

    /**
     * @var string
     */
    protected $filePath = 'storage/app/';

    /**
     * @var SyncDataServiceInterface
     */
    protected $dataParserService;

    /**
     * ParseCsvCommand constructor.
     * @param SyncDataServiceInterface $dataService
     */
    public function __construct(SyncDataServiceInterface $dataService)
    {
        $this->dataParserService = $dataService;
        parent::__construct();
    }

    /**
     * Process command
     */
    public function handle()
    {
        $fileName = $this->argument('file');
        $this->dataParserService->sync($this->filePath . $fileName);
    }

}