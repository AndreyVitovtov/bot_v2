<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $languages_id)
 * @method static find(int $param)
 * @property mixed name
 * @property mixed code
 * @property mixed|string emoji
 * @property mixed id
 */
class Language extends Model
{
    protected $table = 'languages';
    public $timestamps = false;
    public $fillable = [
        'id',
        'name',
        'code',
        'emoji'
    ];
}
