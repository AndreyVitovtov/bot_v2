<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(mixed $id)
 * @method static where(string $string, mixed $id)
 */
class Answer extends Model
{
    protected $table = "answers";
    public $timestamps = false;
    public $fillable = [
        'question',
        'answer',
        'method'
    ];

    public static function toAnswerIfExistQuestion(string $question)
    {
        $answersJson = file_get_contents(public_path("json/answers.json"));
        $answers = json_decode($answersJson);
        foreach ($answers as $answer) {
            if ($answer->question == $question) return $answer;
        }
        return null;
    }
}
