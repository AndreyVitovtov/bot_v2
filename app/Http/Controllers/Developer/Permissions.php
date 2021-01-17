<?php

namespace App\Http\Controllers\Developer;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Permissions extends Controller {
    public function index() {
        return view('developer.permissions.index', [
            'permissions' => Permission::all(),
            'menuItem' => 'permissions'
        ]);
    }

    public function add(Request $request) {
        $permission = new Permission();
        $permission->name = $request->post('permission');
        $permission->save();
        return redirect()->to(route('permissions'));
    }

    public function delete(Request $request) {
        Permission::where('id', $request->post('id'))->delete();
        return redirect()->to(route('permissions'));
    }
}
