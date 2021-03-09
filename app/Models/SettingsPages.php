<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

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
