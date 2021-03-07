<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method where(string $string, int|null $getUserId)
 * @property int|mixed|null users_id
 * @property mixed|string command
 * @property false|mixed|string params
 */
class Interaction extends Model
{
    protected $table = "interaction";
    public $timestamps = false;
    public $fillable = [
        'users_id',
        'command',
        'params'
    ];
}
