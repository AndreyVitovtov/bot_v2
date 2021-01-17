<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model {
    protected $table = "interaction";
    public $timestamps = false;
    public $fillable = [
        'users_id',
        'command',
        'params'
    ];
}
