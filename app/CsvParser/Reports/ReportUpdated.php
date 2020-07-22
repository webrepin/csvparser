<?php
namespace App\CsvParser\Reports;


/**
 * Class ReportUpdated
 * @package App\CsvParser\Reports
 */
class ReportUpdated implements ReportingInterface
{

    /**
     * @var array
     */
    protected $changes;

    /**
     * ReportValidationFailed constructor.
     * @param array $oldData
     * @param array $data
     */
    public function __construct(array $oldData, array $data)
    {
        $this->data = $data;
        $this->oldData = $oldData;
    }

    /**
     * @return array
     */
    public function ReportInfo()
    {
        return $this->changes;
    }

    /**
     *
     */
    public function ReportType(): string
    {
        return 'updated';
    }
}