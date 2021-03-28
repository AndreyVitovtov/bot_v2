<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Seeder;
use App\Models\SettingsButtons;
use App\Models\SettingsMain;
use App\Models\SettingsPages;
use App\Models\User;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Settings extends Controller
{
    private function settingsToJson($arr, $file_name)
    {
        foreach ($arr as $r) {
            $res[$r->name] = base64_decode($r->text);
        }
        file_put_contents(public_path("json/{$file_name}.json"), json_encode($res ?? []));
    }

    public function main()
    {
        return view('admin.settings.settings-main', [
            'fields' => SettingsMain::all(),
            'menuItem' => 'settingsmain'
        ]);
    }

    public function pages($lang = 0)
    {
        $view = view('admin.settings.settings-pages');
        $view->l = $lang;
        $view->menuItem = "settingspages";
        $view->languages = Language::all();

        if ($lang === 0) {
            $view->fields = SettingsPages::paginate(15);
        } else {
            $view->fields = DB::table('settings_pages_' . $lang)->paginate(15);
        }
        return $view;
    }

    public function pagesGoLang(Request $request)
    {
        if ($request->get('lang') != '0') {
            return redirect()->to('admin/settings/pages/' . $request->get('lang'));
        } else {
            return redirect()->to(route('settings-pages'));
        }
    }

    public function buttons($lang = 0)
    {
        $view = view('admin.settings.settings-buttons');
        $view->l = $lang;
        $view->menuItem = "settingsbuttons";

        // TODO: [DEV] VIEW BUTTONS VIBER
        $view->viewButtons = SettingsButtons::getView();
        $view->languages = Language::all();
        if ($lang === 0) {
            $view->fields = SettingsButtons::paginate(15);
        } else {
            $view->fields = DB::table('settings_buttons_' . $lang)->paginate(15);
        }
        return $view;
    }

    public function buttonsGoLang(Request $request)
    {
        if ($request->get('lang') != '0') {
            return redirect()->to('admin/settings/buttons/' . $request->get('lang'));
        } else {
            return redirect()->to(route('settings-buttons'));
        }
    }

    public function mainSave(Request $request)
    {
        $param = $request->input()['input'];
        $paramsWebhook = [];
        $settingsMain = SettingsMain::where('prefix', 'viber_token')
            ->orWhere('prefix', 'telegram_token')
            ->get(['prefix', 'value'])
            ->toArray();

        foreach ($settingsMain as $sm) {
            if ($sm['prefix'] == 'viber_token') {
                if ($param[1] != $sm['value']) {
                    $paramsWebhook['viber'] = true;
                }
            } elseif ($sm['prefix'] == 'telegram_token') {
                if ($param[3] != $sm['value']) {
                    $paramsWebhook['telegram'] = true;
                }
            }
        }

        foreach ($param as $id => $value) {
            SettingsMain::where('id', $id)->update(['value' => $value]);
        }

        $main = SettingsMain::all('prefix', 'value');
        foreach ($main as $r) {
            $res[$r['prefix']] = $r['value'];
        }

        file_put_contents(public_path("json/main.json"), json_encode($res));

        $webhook = new Webhook();
        $webhook->set($paramsWebhook);

        Seeder::setMain();

        return redirect()->to("/admin/settings/main");
    }

    public function pagesEdit(Request $request)
    {
        $id = $request->input()['id'];
        $lang = $request->post('lang');
        if (empty($id)) return redirect()->to("/admin/settings/pages");

        $view = view('admin.settings.settings-pages-edit');
        if ($lang == '0') {
            $view->page = SettingsPages::find($id);
        } else {
            $view->page = DB::select("SELECT * FROM settings_pages_" . $lang . " WHERE id = '" . $id . "' LIMIT 1")[0];
        }
        $view->menuItem = "settingspages";
        $view->lang = $lang;
        return $view;
    }

    public function pagesSave(Request $request)
    {
        $id = $request->input()['id'];
        $text = base64_encode($request->input()['text']);
        $lang = $request->post('lang');
        if ($lang == '0') {
            SettingsPages::where('id', $id)->update(['text' => $text]);
            $pages = SettingsPages::all('name', 'text');
            $this->settingsToJson($pages, "pages");

            $redirect = "/admin/settings/pages";
        } else {
            DB::update("UPDATE settings_pages_" . $lang . " SET text = '" . $text . "' WHERE id = '" . $id . "'");
            $pages = DB::select("SELECT name, text FROM settings_pages_" . $lang);
            $this->settingsToJson($pages, "pages_" . $lang);

            $redirect = "/admin/settings/pages/" . $lang;
        }

        Seeder::setPages();

        return redirect()->to($redirect);
    }

    public function buttonsEdit(Request $request)
    {
        $id = $request->input()['id'];
        $lang = $request->post('lang');
        if (empty($id)) return redirect()->to("/admin/settings/buttons");

        $view = view('admin.settings.settings-buttons-edit');
        if ($lang == '0') {
            $view->button = SettingsButtons::find($id);
        } else {
            $view->button = DB::select("SELECT * FROM settings_buttons_" . $lang . " WHERE id = '" . $id . "' LIMIT 1")[0];
        }
        $view->menuItem = "settingsbuttons";
        $view->lang = $lang;
        return $view;
    }

    public function buttonsSave(Request $request)
    {
        $id = $request->input()['id'];
        $text = base64_encode($request->input()['text']);
        $lang = $request->post('lang');
        if ($lang == '0') {
            SettingsButtons::where('id', $id)->update(['text' => $text]);
            $buttons = SettingsButtons::all('name', 'text');
            $this->settingsToJson($buttons, "buttons");
            $redirect = "/admin/settings/buttons";
        } else {
            DB::update("UPDATE settings_buttons_" . $lang . " SET text = '" . $text . "' WHERE id = '" . $id . "'");
            $pages = DB::select("SELECT name, text FROM settings_buttons_" . $lang);
            $this->settingsToJson($pages, "buttons_" . $lang);

            $redirect = "/admin/settings/buttons/" . $lang;
        }

        Seeder::setButtons();

        return redirect()->to($redirect);
    }

    public function admin()
    {
        return view('admin.settings.settings-admin');
    }

    public function adminUpdate(Request $request)
    {
        $params = $request->input();

        if (!empty($params['password']) && $params['password'] == $params['confirm_password']) {
            $password = Hash::make($params['password']);
            User::where('login', Auth::user()->login)->update(['password' => $password]);
        }

        if (!empty($params['name'])) {
            User::where('login', Auth::user()->login)->update(['name' => $params['name']]);
        }

        if (!empty($params['login'])) {
            User::where('login', Auth::user()->login)->update(['login' => $params['login']]);
        }

        if (!empty($params['name_bot'])) {
            $textsRu = json_decode(file_get_contents(public_path('json/lang/ru/pages.json')), true);
            $textsRu['bot_name'] = $params['name_bot'];
            file_put_contents(public_path('json/lang/ru/pages.json'), json_encode($textsRu));

            $textsUs = json_decode(file_get_contents(public_path('json/lang/us/pages.json')), true);
            $textsUs['bot_name'] = $params['name_bot'];
            file_put_contents(public_path('json/lang/us/pages.json'), json_encode($textsUs));
        }

        return redirect()->to("/logout");
    }

    /* VIBER */
    public function buttonsViewSave(Request $request)
    {
        SettingsButtons::setView(
            $request->post('background'),
            $request->post('color_text'),
            (int)$request->post('size_text')
        );
        return redirect()->back();
    }
}
