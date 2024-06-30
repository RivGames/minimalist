<?php

declare(strict_types=1);

namespace App\CustomQueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class TemporaryLogBuilder extends Builder
{
    /**
     * @param int $chatId
     * @return TemporaryLogBuilder
     */
    public function byChatId(int $chatId): TemporaryLogBuilder
    {
        return $this->where('chat_id', $chatId);
    }
}
