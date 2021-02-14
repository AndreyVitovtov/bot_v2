<?php


namespace App\Http\Controllers\Developer;


use App\Http\Controllers\Controller;
use App\Models\Seeder;
use App\Models\SettingsButtons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class Menu extends Controller
{
    public function list()
    {
        $view = view('developer.menu.list');
        $view->menuItem = "menu-list";
        $view->menu = array_diff(scandir(public_path() . "/json/menu"), array('..', '.'));
        return $view;
    }

    public function add()
    {
        $view = view('developer.menu.add');
        $view->menuItem = "menu-add";
        return $view;
    }

    private function settingsToJson($arr, $file_name)
    {
        foreach ($arr as $r) {
            $res[$r['name']] = base64_decode($r['text']);
        }

        file_put_contents(public_path("json/{$file_name}.json"), json_encode($res));
    }

    private function getButtons($menu, $name): array
    {
        $buttons = [];
        foreach ($menu as $m) {
            foreach ($m as $button) {
                $button['text'] = base64_encode($button['text']);
                $button['menu'] = $name;
                $buttons[] = $button;
            }
        }
        return $buttons;
    }

    public function save(Request $request) {
        $menu = $request->input();
        $names = [];
        $buttons = [];
        foreach($menu['menu'] as $key => $arr) {
            foreach($arr as $a) {
                $names[$a['name']] = $a['text'];
                $buttons[$key][] = $a['name'];
            }
        }

        file_put_contents(public_path() . "/json/menu/" . $menu['name'] . ".json", json_encode($buttons));

        $presents = SettingsButtons::whereIn('name', array_keys($names))->get('name')->toArray();
        if (is_array($presents) && !empty($presents)) {
            foreach ($presents as $p) {
                array_filter($names, function ($name) use ($p, &$names) {
                    if ($name == $p['name']) {
                        unset($names[$name]);
                    }
                }, ARRAY_FILTER_USE_KEY);
            }
        }

        foreach ($names as $name => $text) {
            $settingsButtons = new SettingsButtons;
            $settingsButtons->name = $name;
            $settingsButtons->text = base64_encode($text);
            $settingsButtons->menu = $menu['name'];
            $settingsButtons->menu_us = $menu['name'];
            $settingsButtons->save();
        }

        $settingsButtons = SettingsButtons::all('name', 'text');
        $this->settingsToJson($settingsButtons, "buttons");

        Seeder::setButtons();

        return json_encode($names);
    }

    public function get(Request $request)
    {
        $menuPath = public_path() . '/json/menu/' . $request->get('menu') . ".json";
        if (!file_exists($menuPath)) return "{}";

        $json = json_decode(file_get_contents($menuPath));
        $menu = [];

        $buttons = array_flip(json_decode(file_get_contents(public_path('json/buttons.json')), true));

        foreach($json as $key => $m) {
            foreach($m as $b) {
                $menu[$key][] = [
                    'name' => $b,
                    'text' => array_search($b, $buttons)
                ];
            }
        }

        return json_encode($menu);
    }

    public function delete(Request $request)
    {
        $menuName = $request->get('menuName');
        SettingsButtons::where('menu', $menuName)->delete();
        $menuPath = public_path() . '/json/menu/' . $menuName . ".json";

        Seeder::setButtons();

        if (file_exists($menuPath)) {
            unlink($menuPath);
            return "{success}";
        } else {
            return "{no menu}";
        }
    }

    public function editSave(Request $request)
    {
        $menu = $request->input();
        $names = [];
        $buttons = [];
        foreach($menu['menu'] as $key => $arr) {
            foreach($arr as $a) {
                $names[$a['name']] = $a['text'];
                $buttons[$key][] = $a['name'];
            }
        }

        file_put_contents(public_path() . "/json/menu/" . $menu['name'] . ".json", json_encode($buttons));

        $presents = SettingsButtons::whereIn('name', array_keys($names))->get('name')->toArray();
        if (is_array($presents) && !empty($presents)) {
            foreach ($presents as $p) {
                array_filter($names, function ($name) use ($p, &$names) {
                    if ($name == $p['name']) {
                        unset($names[$name]);
                    }
                }, ARRAY_FILTER_USE_KEY);
            }
        }

//        SettingsButtons::where('menu', $menu['name'])->delete();

        foreach ($names as $name => $text) {
            $settingsButtons = new SettingsButtons;
            $settingsButtons->name = $name;
            $settingsButtons->text = base64_encode($text);
            $settingsButtons->menu = $menu['name'];
            $settingsButtons->menu_us = $menu['name'];
            $settingsButtons->save();
        }

        $settingsButtons = SettingsButtons::all('name', 'text');
        $this->settingsToJson($settingsButtons, "buttons");

        Seeder::setButtons();

        return json_encode($names);
    }
}
