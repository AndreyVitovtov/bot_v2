<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static paginate(int $int)
 * @method static find(mixed $id)
 * @method static where(string $string, mixed $id)
 */
class SettingsButtons extends Model
{
    public $table = 'settings_buttons_ru';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'text',
        'menu',
        'menu_us'
    ];

    public static function setView(string $background, string $colorText, int $sizeText): void
    {
        file_put_contents(public_path('json/buttonsView.json'), json_encode([
            'background' => $background,
            'color_text' => $colorText,
            'size_text' => $sizeText
        ]));
    }

    public static function getView()
    {
        return json_decode(file_get_contents(public_path('json/buttonsView.json')));
    }
}
