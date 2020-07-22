<?php
namespace App\CsvParser\Reports;


/**
 * Interface ReportingInterface
 * @package App\CsvParser\Reports
 */
interface ReportingInterface
{
    /**
     * Get report info from report instance
     * @return mixed
     */
    public function ReportInfo();

    /**
     * Get report type
     * @return string
     */
    public function ReportType(): string ;
}