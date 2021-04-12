<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class MenuAdmin extends Controller {
    public function index() {
        $menu = array_keys($this->getMenu());
        return view('developer.menuAdmin.index', [
            'menu' => $menu,
            'menuItem' => 'developeradminmenuadd'
        ]);
    }

    public function save(Request $request) {
        $request = $request->post();
        unset($request['_token']);
        $menu = $this->getMenu();

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
        if(!Permission::where('name', $request['nameItem'] ?? $request['name'])->exists()) {
            $permission = new Permission();
            $permission->name = $request['nameItem'] ?? $request['name'];
            $permission->save();
        }

        $this->saveMenu($menu);
        return redirect()->to(route('menu-admin-list'));
    }

    public function list() {
        return view('developer.menuAdmin.list', [
            'menus' => $this->getMenu(),
            'menuItem' => 'developeradminmenulist'
        ]);
    }

    public function edit(Request $request) {
//        dd($this->getMenu()[$request->post('key')]);
        $menu = $this->getMenu();
        return view('developer.menuAdmin.edit', [
            'key' => $request->post('key'),
            'menu' => $menu[$request->post('key')],
            'menuItem' => 'developeradminmenulist'
        ]);
    }

    public function editSave(Request $request) {
        $request = $request->post();
        $items = [];
        if($request['type'] == 'rolled') {
            foreach($request['itemName'] as $key => $value) {
                if(empty($request['itemName'][$key]) ||
                    empty($request['itemMenu'][$key]) ||
                    empty($request['itemUrl'][$key])
                ) continue;
                $items[] = [
                    'name' => $request['itemName'][$key],
                    'menu' => $request['itemMenu'][$key],
                    'url' => $request['itemUrl'][$key]
                ];
            }
        }
        $menu = $this->getMenu();
        $menu[$request['key']] = ($request['type'] == 'item') ? [
            'type' => $request['type'],
            'name' => $request['name'],
            'icon' => $request['icon'],
            'menu' => $request['menu'],
            'url' => $request['url']
        ] : [
            'type' => $request['type'],
            'nameItem' => $request['nameItem'],
            'icon' => $request['icon'],
            'name' => $request['name'],
            'items' => $items
        ];
        $this->saveMenu($menu);
        return redirect()->to(route('menu-admin-list'));
    }

    public function delete(Request $request) {
        $menu = $this->getMenu();
        unset($menu[$request->post('key')]);
        $this->saveMenu($menu);

        Permission::where('name', $request->post('key'))->delete();

        return redirect()->to(route('menu-admin-list'));
    }

    public function sortSave(Request $request)
    {
        $request = $request->post();
        $menuAdmin = json_decode(file_get_contents(public_path('json/menu-admin.json')), true);

        $newMenu = [];

        foreach($request['ids'] as $key) {
            $newMenu[$key] = $menuAdmin[$key];
        }
        file_put_contents(public_path('json/menu-admin.json'), json_encode($newMenu));
        print_r($request['ids']);
    }

    private function getMenu() {
        return json_decode(file_get_contents(public_path('json/menu-admin.json')), true);
    }

    private function saveMenu($menu) {
        file_put_contents(public_path('json/menu-admin.json'), json_encode($menu));
    }
}
