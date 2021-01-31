<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Dejurin\GoogleTranslateForFree;
use Illuminate\Http\Request;

class Lang extends Controller {
    public function menuList() {
        $textsRu = $this->getMenuTexts();
        ksort($textsRu);
        return view('developer.lang.menu-list', [
            'textsRu' => $textsRu,
            'textsUs' => $this->getMenuTexts('us'),
            'menuItem' => 'developerlangmenulist'
        ]);
    }

    public function menuAdd() {
        return view('developer.lang.menu-add', [
            'menuItem' => 'developerlangmenuadd'
        ]);
    }

    public function menuAddSave(Request $request) {
        $request = $request->post();

        $menuRu = ($this->getMenuTexts()) ?? [];
        $menuRu[$request['key']] = $request['text'];
        $this->saveMenuTexts($menuRu, 'ru');
        $menuUs = ($this->getMenuTexts('us')) ?? [];
        $menuUs[$request['key']] = GoogleTranslateForFree::translate(
            'ru',
            'us',
            $request['text'],
            5
        );
        $this->saveMenuTexts($menuUs, 'us');

        return redirect()->to(route('lang-menu-add'));
    }

    public function menuEdit(Request $request) {
        $request = $request->post();
        return view('developer.lang.menu-edit', [
            'key' => $request['key'],
            'textRu' => $this->getMenuTexts()[$request['key']],
            'textUs' => $this->getMenuTexts('us')[$request['key']],
            'menuItem' => 'developerlangmenulist'
        ]);
    }

    public function menuEditSave(Request $request) {
        $request = $request->post();
        $textsRu = $this->getMenuTexts();
        $textsUs = $this->getMenuTexts('us');
        $this->changeKey($request['key'], $request['newKey'], $textsRu);
        $this->changeKey($request['key'], $request['newKey'], $textsUs);
        $textsRu[$request['newKey']] = $request['textRu'];
        $textsUs[$request['newKey']] = $request['textUs'];
        $this->saveMenuTexts($textsRu, 'ru');
        $this->saveMenuTexts($textsUs, 'us');
        return redirect()->to(route('lang-menu-list'));
    }

    public function menuDelete(Request $request) {
        $request = $request->post();
        $textsRu = $this->getMenuTexts();
        $textsUs = $this->getMenuTexts('us');
        unset($textsRu[$request['key']]);
        unset($textsUs[$request['key']]);
        $this->saveMenuTexts($textsRu, 'ru');
        $this->saveMenuTexts($textsUs, 'us');
        return redirect()->to(route('lang-menu-list'));
    }

    public function pagesList() {
        $textsRu = $this->getPagesTexts();
        ksort($textsRu);
        return view('developer.lang.pages-list', [
            'textsRu' => $textsRu,
            'textsUs' => $this->getPagesTexts('us'),
            'menuItem' => 'developerlangpageslist'
        ]);
    }

    public function pagesAdd() {
        return view('developer.lang.pages-add', [
            'menuItem' => 'developerlangpagesadd'
        ]);
    }

    public function pagesAddSave(Request $request) {
        $request = $request->post();
        $pagesRu = ($this->getPagesTexts()) ?? [];
        $pagesRu[$request['key']] = $request['text'];
        $this->savePagesTexts($pagesRu, 'ru');
        $pagesUs = ($this->getPagesTexts('us')) ?? [];
        $pagesUs[$request['key']] = GoogleTranslateForFree::translate(
            'ru',
            'us',
            $request['text'],
            5
        );
        $this->savePagesTexts($pagesUs, 'us');
        return redirect()->to(route('lang-pages-add'));
    }

    public function pagesEdit(Request $request) {
        $request = $request->post();
        return view('developer.lang.pages-edit', [
            'key' => $request['key'],
            'textRu' => $this->getPagesTexts()[$request['key']],
            'textUs' => $this->getPagesTexts('us')[$request['key']],
            'menuItem' => 'developerlangpageslist'
        ]);
    }

    public function pagesEditSave(Request $request) {
        $request = $request->post();
        $textsRu = $this->getPagesTexts();
        $textsUs = $this->getPagesTexts('us');
        $this->changeKey($request['key'], $request['newKey'], $textsRu);
        $this->changeKey($request['key'], $request['newKey'], $textsUs);
        $textsRu[$request['newKey']] = $request['textRu'];
        $textsUs[$request['newKey']] = $request['textUs'];
        $this->savePagesTexts($textsRu, 'ru');
        $this->savePagesTexts($textsUs, 'us');

        return redirect()->to(route('lang-pages-list'));
    }

    public function pagesDelete(Request $request) {
        $request = $request->post();
        $textsRu = $this->getPagesTexts();
        $textsUs = $this->getPagesTexts('us');
        unset($textsRu[$request['key']]);
        unset($textsUs[$request['key']]);
        $this->savePagesTexts($textsRu, 'ru');
        $this->savePagesTexts($textsUs, 'us');
        return redirect()->to(route('lang-pages-list'));
    }




    private function getMenuTexts($lang = 'ru') {
        return json_decode(file_get_contents(public_path('json/lang/'.$lang.'/menu.json')), true);
    }

    private function getPagesTexts($lang = 'ru') {
        return json_decode(file_get_contents(public_path('json/lang/'.$lang.'/pages.json')), true);
    }

    private function saveMenuTexts($texts, $lang) {
        file_put_contents(public_path('json/lang/'.$lang.'/menu.json'), json_encode($texts));
    }

    private function savePagesTexts($texts, $lang) {
        file_put_contents(public_path('json/lang/'.$lang.'/pages.json'), json_encode($texts));
    }

    private function changeKey($key, $new_key, &$arr, $rewrite = true) {
        if (!array_key_exists($new_key, $arr) || $rewrite) {
            $arr[$new_key] = $arr[$key];
            unset($arr[$key]);
            return true;
        }
        return false;
    }
}
