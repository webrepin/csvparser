<?php
namespace App\CsvParser\Services;


/**
 * Service is sync data from array rows with database
 *
 * After complete triggered events
 *
 * Interface SyncDataServiceInterface
 * @package App\CsvParser\Services
 */
interface SyncDataServiceInterface
{
    /**
     * @param string $file
     */
    public function sync(string $file):void;
}