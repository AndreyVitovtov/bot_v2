<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TodoStatusModel extends Model
{
    protected $table = 'todo_status';
    public $timestamps = false;
    public $fillable = [
        'id',
        'name'
    ];

    public function todo(): HasMany
    {
        return $this->hasMany(TodoModel::class, 'status');
    }
}
