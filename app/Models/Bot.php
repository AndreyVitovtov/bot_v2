<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $table = 'bots';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'messengers_id',
        'languages_id',
        'token'
    ];

    public function messenger()
    {
        return $this->belongsTo(Messenger::class, 'messengers_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'languages_id');
    }
}
