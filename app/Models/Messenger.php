<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $messenger)
 */
class Messenger extends Model
{
    protected $table = 'messengers';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name'
    ];
}
