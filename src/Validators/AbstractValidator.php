<?php

declare(strict_types=1);

namespace App\Validators;

use App\Core\TelegramService;
use App\DataTransferObjects\ReplyMarkups\ForceReplyDTO;
use Slim\App;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractValidator
{
    protected TranslatorInterface $translator;
    protected TelegramService $telegramService;

    public function __construct(App $app)
    {
        $this->translator = $app->getContainer()->get(TranslatorInterface::class);
        $this->telegramService = $app->getContainer()->get(TelegramService::class);
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @param string $attribute
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function hasErrors(ConstraintViolationListInterface $errors): bool
    {
        return $errors->count() !== 0;
    }

    /**
     * @param string $attribute
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function throwValidationErrorMessage(string $attribute, int|string ...$args): void
    {
        $this->telegramService->sendMessage(
            $this->translator->trans(sprintf('validation.errors.invalid.' . $attribute, $args)),
            $_SESSION['chat_id'],
            ForceReplyDTO::make(true),
        );
    }
}
