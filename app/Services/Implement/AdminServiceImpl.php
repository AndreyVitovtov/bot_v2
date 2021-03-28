<?php


namespace App\Services\Implement;


use App\Models\Admin;
use App\Services\Contracts\AdminService;

class AdminServiceImpl implements AdminService
{

    function create(array $a): int
    {
        $admin = new Admin();
        $admin->login = $a['login'];
        $admin->password = $a['password'];
        $admin->name = $a['name'];
        $admin->language = $a['language'];
        $admin->role_id = $a['role_id'];
        $admin->save();

        return $admin->id;
    }

    function edit(int $id, array $a): void
    {
        $admin = Admin::find($id);
        $admin->login = $a['login'];
        $admin->password = $a['password'];
        $admin->name = $a['name'];
        $admin->language = $a['language'];
        $admin->role_id = $a['role_id'];
        $admin->save();
    }

    function delete(int $id): void
    {
        Admin::where('id', $id)->delete();
    }

    function editRole(int $id, int $role_id)
    {
        $admin = Admin::find($id);
        $admin->role_id = $role_id;
        $admin->save();
    }

    function getRole(int $id)
    {
        $admin = Admin::find($id);
        return $admin->role;
    }
}
