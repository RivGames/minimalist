<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\CustomDataCast;
use App\CustomQueryBuilders\TemporaryLogBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $chat_id
 * @property array $data
 * @property string $created_at
 * @property string $updated_at
 * @method static TemporaryLogBuilder|TemporaryLog byChatId($value)
 */
class TemporaryLog extends Model
{
    protected $fillable = [
        'chat_id',
        'data',
    ];

    protected $casts = [
        'data' => CustomDataCast::class,
    ];

    /**
     * @param $query
     * @return TemporaryLogBuilder
     */
    public function newEloquentBuilder($query): TemporaryLogBuilder
    {
        return new TemporaryLogBuilder($query);
    }
}
