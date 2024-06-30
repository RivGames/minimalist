<?php

namespace App\Enums;

use App\Services\AbstractService;
use App\Services\AddTaskService;
use App\Services\CompleteTaskService;
use App\Services\DeleteTaskService;
use App\Services\ViewTasksService;

enum ActionStrategy: string
{
    case Add = 'add';
    case Complete = 'complete';
    case Delete = 'delete';
    case View = 'view';

    /**
     * @param string $action
     * @return AbstractService
     */
    public static function getServiceClass(string $action): string
    {
        return match ($action) {
            self::Add->value => AddTaskService::class,
            self::Complete->value => CompleteTaskService::class,
            self::Delete->value => DeleteTaskService::class,
            self::View->value => ViewTasksService::class,
        };
    }
}
