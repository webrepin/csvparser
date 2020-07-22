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
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
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
        return 'created';
    }
}