<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Tasks\CreateTaskAction;
use App\DataTransferObjects\ReplyMarkups\ForceReplyDTO;
use App\Enums\TaskLimit;
use App\Models\Task;
use App\Models\TemporaryLog;
use App\Models\User;
use Carbon\Carbon;
use DateTimeInterface;
use Slim\App;

use function Symfony\Component\Clock\now;

class AddTaskService extends AbstractService
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
        // TODO: move $_SESSION['chat_id'] class and use DI
        parent::run($date);
        if ($this->isUserExceededDailyLimit($date)) {
            $this->telegramService->sendMessage(
                $this->translator->trans('errors.business.user-exceeded-daily-limit'),
                $_SESSION['chat_id'],
            );

            return;
        }
        $this->askTitle();
    }

    /**
     * @param TemporaryLog $log
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     * @throws \DateMalformedStringException
     */
    public function save(TemporaryLog $log): void
    {
        $carbon = new Carbon($log->data['date']);
        if ($carbon->isYesterday()) {
            $log->data = ['date' => Carbon::today()->toDateTimeString()];
        }
        $task = (new CreateTaskAction())->handle($log);

        $log->delete();

        $this->viewTasksService->run($task->date);
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function askDay(): void
    {
        $this->telegramService->sendMessage(
            "Please specify day on which do you want to create task",
            $_SESSION['chat_id'],
            ForceReplyDTO::make(true)
        );
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function askTitle(): void
    {
        $this->telegramService->sendMessage(
            $this->translator->trans('ask-task-title'),
            $_SESSION['chat_id'],
            ForceReplyDTO::make(true)
        );
    }

    private function isUserExceededDailyLimit(string $date): bool
    {
        $user = User::byChatId($_SESSION['chat_id'])->first();
        $userTasks = $user->tasks()->byDate(new Carbon($date))->get();

        return count($userTasks) >= TaskLimit::getLimit($user->is_premium);
    }
}
