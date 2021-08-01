<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array $array)
 * @method static where(string $string, $id)
 * @property mixed referrer
 * @property mixed referral
 * @property false|mixed|string datetime
 */
class RefSystem extends Model
{
    protected $table = 'referral_system';
    public $timestamps = false;
    public $fillable = [
        'referrer',
        'referral',
        'datetime'
    ];
}
