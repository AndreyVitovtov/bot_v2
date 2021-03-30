<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static paginate(int $int)
 * @method static find(mixed $id)
 * @method static where(string $string, mixed $id)
 */
class SettingsPages extends Model
{
    public $table = "settings_pages_ru";
    public $timestamps = false;
    public $fillable = [
        'name',
        'text',
        'description',
        'description_us'
    ];
}
