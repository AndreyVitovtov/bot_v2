<?php


namespace App\Services\Implement;


use App\Models\Language;
use App\Services\Contracts\LanguageService;

class LanguageServiceImpl implements LanguageService
{

    function create(array $l): int
    {
        $language = new Language();
        $language->name = $l['name'];
        $language->code = $l['code'];
        $language->emoji = base64_encode($l['emoji']);
        $language->save();

        return $language->id;
    }

    function delete(int $id)
    {
        Language::where('id', $id)->delete();
    }
}
