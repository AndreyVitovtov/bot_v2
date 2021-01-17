<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {
    protected $table = "permissions";
    public $timestamps = false;
    public $fillable = [
        'name'
    ];
}
