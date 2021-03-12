<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static groupBy(string $string)
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
}
