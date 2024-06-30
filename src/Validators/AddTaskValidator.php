<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\TemporaryLog;
use App\Services\AddTaskService;
use Slim\App;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class AddTaskValidator extends AbstractValidator
{
    protected AddTaskService $addTaskService;

    /**
     * @param App $app
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->addTaskService = new AddTaskService($app);
    }

    /**
     * @param string $text
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function title(string $text): void
    {
        $title = trim($text);
        $validator = Validation::createValidator();
        $errors = $validator->validate($title, [
            new Length(['min' => 3, 'max' => 50]),
            new NotBlank(),
        ]);
        if ($this->hasErrors($errors)) {
            $this->throwValidationErrorMessage('title');

            return;
        }
        // TODO: move to UpdateTemporaryActionAction
        $temporaryLog = TemporaryLog::byChatId($_SESSION['chat_id'])->first();
        $temporaryLog->data = ['title' => $title];
        $temporaryLog->save();

        $this->addTaskService->save($temporaryLog);
    }
}
