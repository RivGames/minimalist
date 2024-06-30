<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Entities\Update;
use App\Enums\AvailableCommand;
use App\Enums\ActionStrategy;
use App\Models\User;
use App\Services\ValidationMapper;
use App\TelegramCommands\AbstractCommand;
use App\TelegramCommands\NotFoundCommand;
use Carbon\Carbon;
use Slim\App;

readonly class WebhookHandler
{
    public Update $update;

    public function __construct(public App $app, array $data)
    {
        $this->update = Update::from($data);
        $_SESSION['chat_id'] = $this->update->message?->chat->id ?? $this->update->callbackQuery?->message->chat->id;
        $_SESSION['locale'] = $this->update->message?->from->languageCode ?? User::byChatId(
            $_SESSION['chat_id']
        )->first()->language_code;
    }

    /**
     * @return void
     */
    public function handle(): bool
    {
        $text = $this->update->message?->text;
        return match (true) {
            !empty($this->update->callbackQuery) => $this->handleCallback(),
            !empty($this->update->message->replyToMessage) => $this->handleReply($text),
            default => $this->handleDefaultCommands($text),
        };
    }

    /**
     * @param string $text
     * @return bool
     */
    private function handleCallback(): bool
    {
        [$date, $action, $id] = explode('/', $this->update->callbackQuery->data);
        $class = ActionStrategy::getServiceClass($action);
        $taskService = new $class($this->app);
        $taskService->run($date);

        return true;
    }

    /**
     * @param string $text
     * @return bool
     */
    private function handleReply(string $answer): bool
    {
        [$validatorClass, $method] = (new ValidationMapper())->run($this->update->message->replyToMessage->text);
        if (!empty($validatorClass) && !empty($method)) {
            (new $validatorClass($this->app))->{$method}($answer);

            return true;
        }

        return false;
    }

    /**
     * @param string $text
     * @return bool
     */
    private function handleDefaultCommands(string $text): bool
    {
        if (in_array($text, array_keys(AvailableCommand::all()))) {
            $class = AvailableCommand::all()[$text];
        } else {
            $class = NotFoundCommand::class;
        }
        /** @var AbstractCommand $telegramCommand */
        $telegramCommand = new $class($this->app);
        $telegramCommand->execute($this->update);

        return true;
    }
}
