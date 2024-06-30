<?php

declare(strict_types=1);

namespace App\CustomQueryBuilders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TaskBuilder extends Builder
{
    public function byChatId(int $chatId): TaskBuilder
    {
        return $this->where('chat_id', $chatId);
    }

    public function notCompletedYet(): TaskBuilder
    {
        return $this->where('is_completed', false);
    }

    public function completed(): TaskBuilder
    {
        return $this->where('is_completed', true);
    }

    public function byDate(Carbon $day): TaskBuilder
    {
        return $this->whereDate('date', $day);
    }
}
