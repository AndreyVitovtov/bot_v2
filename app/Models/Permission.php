<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string name
 * @property mixed id
 * @method static where(string $string, int $id)
 */
class Permission extends Model
{
    protected $table = "permissions";
    public $timestamps = false;
    public $fillable = [
        'name'
    ];
}
