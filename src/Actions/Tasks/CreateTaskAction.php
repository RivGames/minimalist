<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Models\Task;
use App\Models\TemporaryLog;

class CreateTaskAction
{
    /**
     * @param TemporaryLog $log
     * @return Task
     */
    public function handle(TemporaryLog $log): Task
    {
        return Task::create([
            'title' => $log->data['title'],
            'date' => $log->data['date'],
            'chat_id' => $_SESSION['chat_id'],
        ]);
    }
}
