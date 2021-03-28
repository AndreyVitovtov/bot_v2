<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static groupBy(string $string)
 * @method static where(string $string, mixed $id)
 * @property mixed|string title
 * @property int|mixed status
 * @property false|mixed|string datetime
 */
class TodoModel extends Model
{
    protected $table = 'todo';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'title',
        'text',
        'status',
        'datetime'
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(TodoStatusModel::class, 'status');
    }
}
