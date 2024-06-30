<?php

declare(strict_types=1);

namespace App\TelegramCommands;

use App\Entities\Update;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Contracts\Translation\TranslatorInterface;

class StartCommand extends AbstractCommand
{
    /**
     * @param Update $update
     * @return void
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function execute(Update $update): void
    {
        // Move to action
        User::query()->updateOrCreate(
            [
                'chat_id' => $update->message->chat->id,
            ],
            [
                'first_name' => $update->message->from->firstName,
                'last_name' => $update->message->from->lastName,
                'username' => $update->message->from->username,
                'language_code' => $update->message->from->languageCode,
                'is_premium' => $update->message->from->isPremium,
            ]
        );

        $this->telegramService->sendMessage(
            $this->translator->trans('commands.start'),
            $update->message->chat->id
        );
    }
}
