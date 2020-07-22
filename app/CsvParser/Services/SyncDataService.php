<?php
namespace App\CsvParser\Services;

use App\Common\Models\User;
use App\Common\Models\UserTempData;
use App\CsvParser\Reports\ReportCreated;
use App\CsvParser\Reports\ReportRestored;
use App\CsvParser\Reports\ReportsManager;
use App\CsvParser\Reports\ReportUpdated;
use App\CsvParser\Reports\ReportValidationFailed;
use Illuminate\Support\Facades\Validator;

/**
 * Class SyncDataService
 * @package App\CsvParser\Services
 */
class SyncDataService implements SyncDataServiceInterface
{
    /**
     * @var User empty Model
     */
    protected $userModel;

    /**
     * @var UserTempData
     */
    protected $userTempData;

    /**
     * @var ReportsManager
     */
    protected $reportsManager;

    /**
     * SyncDataService constructor.
     *
     * @param User $user
     * @param UserTempData $tempData
     * @param ReportsManager $reportsManager
     */
    protected function __construct(User $user, UserTempData $tempData, ReportsManager $reportsManager)
    {
        $this->userModel = $user;
        $this->userTempData = $tempData;
        $this->reportsManager = $reportsManager;
    }

    /**
     * The service will be sync data in database by CSV file source
     *
     * @param string $file
     * @throws \Exception
     * @throws \Throwable
     */
    public function sync(string $file): void
    {
        $handle = fopen($file, "r");

        if ($handle === false) {
            throw new \Exception('Failed to open file');
        }

        //to flush all temp data
        $this->userTempData->newQuery()->truncate();

        $row = 1;
        $bulkInsert = [];
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) != count($this->userModel->getFillable())) {
                throw new \Exception("Failed while parsing CSV file on {$row} line");
            }
            $adaptedRow = $this->adaptData($data);
            $bulkInsert[] = $adaptedRow;
            if(count($bulkInsert) > 1000) {
                $this->userTempData->newQuery()->insert($bulkInsert);
                $bulkInsert = [];
            }
            $row ++;
        }

        // truncate all duplicates in temp data
        $this->filterDuplicates();

        // @todo able to be in transaction
        /** @var UserTempData $tempRow */
        while ($tempRow = $this->userTempData->newQuery()->first()) {
            $tempData = $tempRow->getAttributes();
            $this->processRow($tempData);
            $tempRow->delete();
        }

        fclose($handle);
    }

    /**
     *
     * @todo Validator is able to be encapsulated
     *
     * @param array $data
     * @return mixed
     */
    protected function validateRow(array $data)
    {
        return Validator::make($data, [
            'id' => 'required|unique:App\Common\Models\User,id|integer',
            'card' => 'required|unique:App\Common\Models\User,card|max:30',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|max:255',
        ])->validate();
    }

    /**
     * Clear and prevent processing all of duplicates in dirty data rows
     *
     * Will return count of duplicates
     *
     * @return int
     */
    protected function filterDuplicates() : int
    {
        return $this
            ->userTempData
            ->newQuery()
            ->selectRaw('SELECT *, count(id) as data_count')
            ->groupBy(['id'])
            ->having('data_count', '>', 1)
            ->delete();
    }

    /**
     * Parse not relative to relative array by fillable rows in model
     *
     * @param array $data
     * @return array
     */
    protected function adaptData(array $data)
    {
        $result = [];
        foreach ($this->userModel->getFillable() as $k => $names) {
            $result[$k] = $data[$k] ?? null;
        }
        return $result;
    }

    /**
     * Row processing
     *
     * All the entries that are no longer in the file, but exist in db need to be removed (soft delete)
     * Any new entry - need to be added
     * Any existing entry - should be updated
     * Any entry that is currently deleted in the db, need to be restored.
     *
     * @param array $data
     * @return bool
     */
    protected function processRow(array $data): bool
    {
        $validateErrors = $this->validateRow($data);
        if ($validateErrors) {
            $this->reportsManager->putDataToReportFile(
                //sending report to file like {filename-validation-failed.csv}
                new ReportValidationFailed($data, $validateErrors)
            );
            return false;
        }

        /** @var User $existsRow */
        $existsRow = $this->userModel
            ->newQuery()
            ->where(['id' => $data['id']])
            ->first();

        // if user exist in db then update rows
        if ($existsRow) {
            $this->updateExists($existsRow, $data);
        } else {
            $this->createNew($data);
        }
        //
        $this->softDeletes();
        return true;

    }

    /**
     * Update exists entire with condition - if deleted_at > 0 then deleted_at = null
     * and update another cols
     * or just update
     *
     * After success updating print rows to report
     *
     * @param User $user
     * @param array $data
     */
    protected function updateExists(User $user, array $data)
    {
        if ($user->deleted_at) {
            $data['deleted_at'] = 'null';
            $user->update($data);
            $this->reportsManager->putDataToReportFile(
                new ReportRestored($data)
            );
        } else {
            $user->update($data);
            $this->reportsManager->putDataToReportFile(
                new ReportUpdated($user->getChanges(), $data)
            );
        }
    }

    /**
     * Create new entry into Database
     * After success creating print rows to report
     *
     * @param array $data
     */
    protected function createNew(array $data): void
    {
        $newRow = clone $this->userModel;
        $newRow->fill($data);

        if ($newRow->save()) {
            $this->reportsManager->putDataToReportFile(
                new ReportCreated($data)
            );
        }
    }

    /**
     * Soft delete entries from data base deleted_at = timestamp()
     * After success deleting will print rows to report
     */
    protected function softDeletes(): void
    {
        /* @todo by the JOIN we can fetch all not isset rows and soft delete by the SQL query
            UPDATE user SET deleted_at = timestamp() WHERE id in (SELECT id FROM user left JOIN user_temp_data ... where user_temp_data.id is NULL)
         */
    }
}