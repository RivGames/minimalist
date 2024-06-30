<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\TelegramService;
use App\Models\TemporaryLog;
use Carbon\Carbon;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\App;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractService
{
    protected TelegramService $telegramService;
    protected TranslatorInterface $translator;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(protected App $app)
    {
        $this->telegramService = $this->app->getContainer()->get(TelegramService::class);
        $this->translator = $this->app->getContainer()->get(TranslatorInterface::class);
    }

    /**
     * @param string $date
     * @return void
     */
    public function run(string $date): void
    {
        // TODO: move to CreateTemporaryActionAction
        TemporaryLog::updateOrCreate([
            'chat_id' => $_SESSION['chat_id'],
        ], [
            'data' => ['class' => self::class, 'date' => $date],
        ]);
    }
}
