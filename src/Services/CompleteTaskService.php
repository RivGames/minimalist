<?php

declare(strict_types=1);

namespace App\Services;

use App\DataTransferObjects\ReplyMarkups\ForceReplyDTO;
use App\Models\TemporaryLog;
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
        $this->askTaskNumber();
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function askTaskNumber(): void
    {
        $this->telegramService->sendMessage(
            $this->translator->trans('ask-task-numbers-to-complete'),
            $_SESSION['chat_id'],
            ForceReplyDTO::make(true)
        );
    }

    public function save(TemporaryLog $log): void
    {
        // TODO: Implement save() method.
    }
}
