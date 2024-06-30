<?php

declare(strict_types=1);

namespace App\Services;

use App\DataTransferObjects\ReplyMarkups\ForceReplyDTO;
use App\DataTransferObjects\ReplyMarkups\InlineKeyboardButtonDTO;
use App\DataTransferObjects\ReplyMarkups\InlineKeyboardMarkupDTO;
use App\Enums\Emoji;
use App\Models\Task;
use App\Models\TemporaryLog;
use Carbon\Carbon;
use Slim\App;

class CompleteTaskService extends AbstractService
{
    protected ViewTasksService $viewTasksService;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->viewTasksService = new ViewTasksService($app);
    }

    /**
     * @param string $date
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function run(string $date): void
    {
        parent::run($date);
        $userTasks = Task::byChatId($_SESSION['chat_id'])
            ->byDate(Carbon::today())
            ->notCompletedYet()
            ->get();
        $inlineKeyboard = [];
        for ($i = 0; $i < 3; $i++) {
            if (!empty($userTasks[$i])) {
                $callbackData = $this->createCallbackData($date, $userTasks[$i]);
                $inlineKeyboard[] = $this->createInlineKeyboardButtonDTO($callbackData, $userTasks[$i]);
            }
        }
        $temp = [];
        $userTasksLength = count($userTasks);
        for ($i = 0; $i < $userTasksLength; $i++) {
            if ($i % 3 === 0) {
                $temp = [];
                $inlineKeyboard[] = $temp;
            }
            $callbackData = $this->createCallbackData($date, $userTasks[$i]);
            $temp[] = $this->createInlineKeyboardButtonDTO($callbackData, $userTasks[$i]);
            if ($i === $userTasksLength - 1) {
                $inlineKeyboard[] = $temp;
            }
        }
        $this->telegramService->sendMessage(
            $this->translator->trans('commands.complete.specify-task-number'),
            $_SESSION['chat_id'],
            InlineKeyboardMarkupDTO::make($inlineKeyboard)
        );
    }

    /**
     * @param array $taskIndexes
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function complete(array $taskIndexes): void
    {
        // TODO: move to CompleteTasksAction
        $temporaryLog = TemporaryLog::byChatId($_SESSION['chat_id'])->first();
        Task::whereIn('index', $taskIndexes)
            ->byChatId($_SESSION['chat_id'])
            ->byDate(new Carbon($temporaryLog->data['date']))
            ->update(['is_completed' => 1]);
        $this->viewTasksService->run($temporaryLog->data['date']);
    }

    private function createCallbackData(string $date, Task $task): string
    {
        return sprintf('%s /complete/ %d', $date, $task->index);
    }

    private function createInlineKeyboardButtonDTO(string $callbackData, Task $task): InlineKeyboardButtonDTO
    {
        return InlineKeyboardButtonDTO::make(
            Emoji::getEmojiNumberByIndex($task->index),
            callback_data: $callbackData
        );
    }
}
