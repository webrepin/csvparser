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
     * @inheritdoc
     */
    public function ReportInfo(): array
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function ReportType(): string
    {
        return 'validation_failed';
    }
}