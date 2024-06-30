<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Task;
use App\Services\CompleteTaskService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Slim\App;

class CompleteTaskValidator extends AbstractValidator
{
    protected CompleteTaskService $completeTaskService;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->completeTaskService = new CompleteTaskService($app);
    }

    /**
     * @param string $text
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function taskNumber(string $text): void
    {
        $userTasksCompleted = collect(Task::byChatId($_SESSION['chat_id'])
            ->byDate(Carbon::today())
            ->completed()
            ->get('index')
            ->toArray())
            ->flatten()
            ->toArray();
            $userTasksNotCompleted = collect(Task::byChatId($_SESSION['chat_id'])
                ->byDate(Carbon::today())
                ->notCompletedYet()
                ->get('index')
                ->toArray())
                ->flatten()
                ->toArray();
        preg_match('/(\d{2})|(\d)/', $text, $matches);
        if (empty($matches)) {
            $this->throwValidationErrorMessage('task-number-format');
        }
        $taskIndexes = $this->parseTaskNumbers(array_filter($matches));
//        if (!empty($matches['one_digit'])) {
//            $taskIndexes = $this->parseOneDigit($matches['one_digit'], $userTasksNotCompleted, $taskIndexes);
//        }
//        if (!empty($matches['two_digits'])) {
//            $taskIndexes = $this->parseTwoDigits($matches['two_digits'], $userTasksNotCompleted, $taskIndexes);
//        }
//        if (!empty($matches['with_comma'])) {
//            $taskIndexes = $this->parseWithComma($matches['with_comma'], $userTasksNotCompleted, $taskIndexes);
//        }
//        if (in_array($index, $taskIndexes) && in_array($index, $userTasksIndexes)) {
//            $this->throwValidationErrorMessage('task-not-found', $index);
//        }

        $this->completeTaskService->complete(array_filter($taskIndexes));
    }

    private function parseTaskNumbers(array $matches): array
    {
        $result = [];
        foreach ($matches as $match) {

        }
        return $result;
    }

    /**
     * @param array $indexes
     * @param array $userTasksIndexes
     * @param $taskIndexes
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    private function parseOneDigit(array $indexes, array $userTasksIndexes, $taskIndexes): array
    {
        $results = [];
        foreach ($indexes as $index) {
            $results[] = $index;
        }
        return array_merge($results, $taskIndexes);
    }

    /**
     * @param array $indexes
     * @param array $userTasksIndexes
     * @param $taskIndexes
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    private function parseTwoDigits(array $indexes, array $userTasksIndexes, $taskIndexes): array
    {
        $results = [];
        foreach ($indexes as $index) {
            $results[] = $index;
        }
        return array_merge($results, $taskIndexes);
    }

    /**
     * @param array $indexes
     * @param array $userTasksIndexes
     * @param $taskIndexes
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    private function parseWithComma(array $indexes, array $userTasksIndexes, $taskIndexes): array
    {
        $results = [];
        foreach ($indexes as $index) {
            $index = str_replace(',', '', $index);
            if (in_array($index, $taskIndexes) && in_array($index, $userTasksIndexes)) {
                $this->throwValidationErrorMessage('task-not-found', $index);
            }
            $results[] = $index;
        }
        return array_merge($results, $taskIndexes);
    }
}
