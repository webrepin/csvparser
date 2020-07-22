<?php
namespace App\CsvParser\Reports;


/**
 * Class ReportRestored
 * @package App\CsvParser\Reports
 */
class ReportRestored implements ReportingInterface
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
     * @inheritdoc
     */
    public function ReportInfo():array
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function ReportType(): string
    {
        return 'restored';
    }
}