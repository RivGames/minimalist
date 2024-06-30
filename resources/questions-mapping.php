<?php

use App\Validators\AddTaskValidator;
use App\Validators\CompleteTaskValidator;
use App\Validators\DeleteTaskValidator;

return [
    AddTaskValidator::class => [
        'title' => [1, 2]
    ],
    CompleteTaskValidator::class => [
        'taskNumber' => [3],
    ],
    DeleteTaskValidator::class => [
        'taskNumber' => [4],
    ],
];
