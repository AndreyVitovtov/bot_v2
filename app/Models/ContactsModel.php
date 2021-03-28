<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int|mixed|null users_id
 * @property mixed|string|null text
 * @property false|mixed|string date
 * @property false|mixed|string time
 * @property mixed contacts_type_id
 * @property mixed id
 * @method static where(string $string, int $id)
 * @method static whereIn(string $string, array $ids)
 * @method static find(int $id)
 */
class ContactsModel extends Model
{
    protected $table = 'contacts';
    public $timestamps = false;
    public $fillable = [
        'id',
        'contacts_type_id',
        'users_id',
        'text',
        'date',
        'time'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ContactsType::class, 'contacts_type_id');
    }

    public function users(): HasOne
    {
        return $this->hasOne(BotUsers::class, 'id', 'users_id');
    }
}
