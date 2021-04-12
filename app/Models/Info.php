<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static first()
 * @property mixed db_address
 * @property mixed db_login
 * @property mixed db_password
 * @property mixed db_name
 * @property mixed ftp_type
 * @property mixed ftp_address
 * @property mixed ftp_password
 * @property mixed ftp_login
 */
class Info extends Model
{
    protected $table = 'info';
    public $timestamps = false;
    protected $fillable = [
        'db_address',
        'db_login',
        'db_password',
        'db_name',
        'ftp_type',
        'ftp_address',
        'ftp_login',
        'ftp_password'
    ];
}
