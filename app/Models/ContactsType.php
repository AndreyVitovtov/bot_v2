<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, mixed $topic)
 */
class ContactsType extends Model
{
    protected $table = 'contacts_type';
    public $timestamps = false;
    public $fillable = [
        'id',
        'type'
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(ContactsModel::class, 'contacts_type_id');
    }
}
