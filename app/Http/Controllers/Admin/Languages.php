<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Languages extends Controller
{
    public function list()
    {
        return view('admin.languages.list', [
            'languages' => Language::all(),
            'menuItem' => 'languageslist'
        ]);
    }

    public function add()
    {
        return view('admin.languages.add', [
            'menuItem' => 'languagesadd'
        ]);
    }

    public function addSave(Request $request)
    {
        $lang = new Language;
        $lang->name = $request->post('name');
        $lang->code = $request->post('code');
        $lang->emoji = base64_encode($request->post('emoji'));
        $lang->save();

        try {
            DB::beginTransaction();

            $nameTablePages = "settings_pages_" . $request->post('code');
            $nameTableButtons = "settings_buttons_" . $request->post('code');
            DB::connection()->statement("CREATE TABLE IF NOT EXISTS $nameTablePages LIKE settings_pages_ru");
            DB::connection()->statement("INSERT INTO $nameTablePages SELECT * FROM settings_pages_ru");

            DB::connection()->statement("CREATE TABLE IF NOT EXISTS $nameTableButtons LIKE settings_buttons_ru");
            DB::connection()->statement("INSERT INTO $nameTableButtons SELECT * FROM settings_buttons_ru");

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return redirect()->to(route('languages-list'));
    }

    public function delete(Request $request)
    {
        $lang = Language::find($request->post('id'));

        try {
            DB::beginTransaction();

            $nameTablePages = "settings_pages_" . $lang->code;
            $nameTableButtons = "settings_buttons_" . $lang->code;
            DB::connection()->statement("DROP TABLE $nameTablePages");
            DB::connection()->statement("DROP TABLE $nameTableButtons");
            Language::where('id', $request->post('id'))->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        if (file_exists(public_path() . "/json/pages_" . $lang->code . ".json")) {
            unlink(public_path() . "/json/pages_" . $lang->code . ".json");
        }

        if (file_exists(public_path() . "/json/buttons_" . $lang->code . ".json")) {
            unlink(public_path() . "/json/buttons_" . $lang->code . ".json");
        }

        return redirect()->to(route('languages-list'));
    }
}
