<?php

declare(strict_types=1);

namespace App\Models;

use App\CustomQueryBuilders\TaskBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * @property string $title
 * @property string $date
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $completed_at
 * @property int $index
 * @property bool is_completed
 * @method static Builder|Task byChatId($chatId)
 * @method static Builder|Task byDate($date)
 * @method static Builder|Task notCompletedYet()
 * @method static Builder|Task completed()
 */
class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'chat_id',
        'date',
        'index',
        'is_completed',
        'completed_at',
    ];

    public function newEloquentBuilder($query): TaskBuilder
    {
        return new TaskBuilder($query);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chat_id', 'chat_id');
    }
}
