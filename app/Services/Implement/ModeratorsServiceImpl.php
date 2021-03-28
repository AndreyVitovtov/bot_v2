<?php


namespace App\Services\Implement;


use App\Models\Admin;
use App\Services\Contracts\ModeratorsService;
use Illuminate\Support\Facades\Hash;

class ModeratorsServiceImpl implements ModeratorsService
{

    function create(array $m): int
    {
        $moderator = new Admin();
        $moderator->login = $m['login'];
        $moderator->name = $m['name'];
        $moderator->password = Hash::make($m['password']);
        $moderator->roles_id = 2;
        $moderator->save();

        return $moderator->id;
    }

    function delete(int $id): void
    {
        Admin::where('id', '=', $id)->delete();
    }

    function edit(array $m): void
    {
        $moderator = Admin::find($m['id']);
        $moderator->login = $m['login'];
        $moderator->name = $m['name'];
        if (!empty($m['password'])) {
            $moderator->password = Hash::make($m['password']);
        }
        $moderator->save();
    }

    function all()
    {
        return Admin::where('id', '!=', 1)->get();
    }

    function get(int $id): Admin
    {
        return Admin::find($id);
    }
}
