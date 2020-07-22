<?php
namespace App\CsvParser\Reports;


/**
 * Class ReportValidationFailed
 * @package App\CsvParser\Reports
 */
class ReportValidationFailed implements ReportingInterface
{

    /**
     * @var array
     */
    protected $data;
    /**
     * @var array
     */
    protected $errors;

    /**
     * ReportValidationFailed constructor.
     * @param array $data
     * @param array $errors
     */
    public function __construct(array $data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function ReportInfo()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function ReportType(): string
    {
        return 'validation_failed';
    }
}