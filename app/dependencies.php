<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Core\TelegramService;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        TelegramService::class => function () {
            return new TelegramService();
        },
        TranslatorInterface::class => function () {
            $translator = new Translator($_SESSION['locale']);
            $translator->addLoader('array', new PhpFileLoader());
            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/en/telegram.php', 'en');
            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/en/questions.php', 'en');
            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/en/validation.php', 'en');

            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/uk/telegram.php', 'uk');
            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/uk/questions.php', 'uk');
            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/uk/validation.php', 'uk');

            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/ru/telegram.php', 'ru');
            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/ru/questions.php', 'ru');
            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/ru/validation.php', 'ru');

            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/es/telegram.php', 'es');
            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/es/questions.php', 'es');
            $translator->addResource('array', dirname(__DIR__) . '/resources/languages/es/validation.php', 'es');

            return $translator;
        }
    ]);
};
