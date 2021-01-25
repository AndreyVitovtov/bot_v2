<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuAdmin extends Controller {
    public function index() {
        $menu = array_keys(json_decode(file_get_contents(public_path('json/menu-admin.json')), true));
        return view('developer.menuAdmin.index', [
            'menu' => $menu,
            'menuItem' => 'developeradminmenuadd'
        ]);
    }

    public function save(Request $request) {
        $request = $request->post();
        unset($request['_token']);
        $menu = json_decode(file_get_contents('json/menu-admin.json'), true);

        $items = [];
        if($request['type'] == 'rolled') {
            for($i = 0; $i < count($request['item_name']); $i++) {
                $items[] = [
                    'name' => $request['item_name'][$i],
                    'menu' => $request['item_menu'][$i],
                    'url' => $request['item_url'][$i],
                ];
            }
        }

        if($request['add_after'] != 'last') {
            $position = array_search($request['add_after'], array_keys($menu));
            unset($request['add_after']);

            if($request['type'] == 'item') {
                $menu = array_slice($menu, 0, $position + 1, true) +
                    [$request['name'] => $request] +
                    array_slice($menu, $position + 1, NULL, true);
            }
            else {
                $menu = array_slice($menu, 0, $position + 1, true) +
                    [$request['nameItem'] => [
                        'type' => $request['type'],
                        'nameItem' => $request['nameItem'],
                        'icon' => $request['icon'],
                        'name' => $request['name'],
                        'items' => $items
                    ]] +
                    array_slice($menu, $position + 1, NULL, true);
            }
        }
        else {
            if ($request['type'] == 'rolled') {
                $menu[$request['nameItem']] = [
                    'type' => $request['type'],
                    'nameItem' => $request['nameItem'],
                    'icon' => $request['icon'],
                    'name' => $request['name'],
                    'items' => $items
                ];
            } else {
                unset($request['add_after']);
                $menu[$request['name']] = $request;
            }
        }
        file_put_contents(public_path('json/menu-admin.json'), json_encode($menu));
        return redirect()->to(route('menu-admin'));
    }

    public function list() {
        return view('developer.menuAdmin.list', [
            'menus' => json_decode(file_get_contents(public_path('json/menu-admin.json')), true),
            'menuItem' => 'developeradminmenulist'
        ]);
    }
}
