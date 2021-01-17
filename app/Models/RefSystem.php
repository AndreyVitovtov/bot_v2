<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefSystem extends Model {
    protected $table = 'referral_system';
    public $timestamps = false;
    public $fillable = [
        'referrer',
        'referral',
        'date',
        'time'
    ];
}
