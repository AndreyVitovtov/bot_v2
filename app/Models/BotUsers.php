<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find(int|null $getUserId)
 * @method where(string $string, string|null $chat)
 * @method static paginate(int $int)
 * @property mixed|string|null chat
 * @property mixed|string first_name
 * @property mixed|string last_name
 * @property mixed|string username
 * @property mixed|string|null country
 * @property false|mixed|string date
 * @property false|mixed|string time
 * @property mixed id
 * @property mixed languages_id
 * @property mixed language
 * @property mixed messengers_id
 * @property mixed firstname
 * @property mixed lastname
 */
class BotUsers extends Model
{
    protected $table = "users";
    public $timestamps = false;
    public $fillable = [
        'id',
        'chat',
        'username',
        'first_name',
        'last_name',
        'country',
        'messenger',
        'access',
        'date',
        'time',
        'active',
        'start',
        'count_ref',
        'access_free',
        'language'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'languages_id');
    }

    public function messenger()
    {
        return $this->belongsTo(Messenger::Class, 'messengers_id');
    }
}
