<?php

declare(strict_types=1);

use App\Validators\AddTaskValidator;
use App\Validators\CompleteTaskValidator;
use App\Validators\DeleteTaskValidator;

return [
    AddTaskValidator::class => [
        "Great\n Let's add a new task to your to do list for today\n What's the name of the task? Example" => 'title',
        'Error. Min 3. Max 25.' => 'title',
    ],
    CompleteTaskValidator::class => [
        "Please specify the numbers of the tasks you want to complete. Separate multiple task numbers with a comma. For example: `1, 3, 5`" => 'taskNumber'
    ],
    DeleteTaskValidator::class => [
        "Please specify the numbers of the tasks you want to delete. Separate multiple task numbers with a comma. For example: `1, 3, 5`" => 'taskNumber'
    ],
];
