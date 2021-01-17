<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AdminChat extends Model {
    protected $table = "admins_chat";
    public $timestamps = false;
    public $fillable = [
        'name',
        'login',
        'password',
        'chats_id'
    ];
}
