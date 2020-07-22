<?php

namespace App\CsvParser\Reports;


/**
 * Class ReportsManager
 * @package App\CsvParser\Reports
 */
class ReportsManager
{

    /**
     * Writing data to the file
     * @todo may be composite with report data provider and put report to DB etc.
     *
     * @param ReportingInterface $report
     */
    public function putDataToReportFile(ReportingInterface $report)
    {
        $type = $report->ReportType();
        $data = $report->ReportInfo();

        //  @todo fputcsv ( resource $handle , array $data) : int
    }

}