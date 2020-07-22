<?php
namespace App\CsvParser\Reports;


/**
 * Entire of report that holding data about added row to the database
 *
 * Class ReportCreated
 * @package App\CsvParser\Reports
 */
class ReportCreated implements ReportingInterface
{

    /**
     * @var array
     */
    protected $data;

    /**
     * ReportValidationFailed constructor.
     * @param array $oldData
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function ReportInfo()
    {
        return $this->data;
    }

    /**
     *
     */
    public function ReportType(): string
    {
        return 'created';
    }
}