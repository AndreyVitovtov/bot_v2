<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed messengers_id
 * @property mixed languages_id
 * @property mixed name
 * @property mixed token
 * @property mixed id
 * @method static where(string $string, mixed $id)
 * @method static find(mixed $id)
 */
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

    public function users()
    {
        return $this->hasMany(BotUsers::class, 'bots_id');
    }
}
