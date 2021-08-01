<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Permission;
use App\Services\Contracts\ModeratorsService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Moderators extends Controller
{

    /**
     * @var ModeratorsService
     */
    private $moderatorsService;

    public function __construct(ModeratorsService $moderatorsService)
    {
        $this->moderatorsService = $moderatorsService;
    }


    public function permissions()
    {
        return view('admin.moderators.permissions', [
            'moderators' => $this->moderatorsService->all(),
            'permissions' => Permission::all(),
            'menuItem' => 'moderatorspermissions'
        ]);
    }

    public function add()
    {
        return view('admin.moderators.add', [
            'menuItem' => 'moderatorsadd'
        ]);
    }

    public function addSave(Request $request)
    {
        $this->moderatorsService->create($request->input());
        return redirect()->to(route('moderators-list'));
    }

    public function list()
    {
        return view('admin.moderators.list', [
            'moderators' => $this->moderatorsService->all(),
            'menuItem' => 'moderatorslist'
        ]);
    }

    public function edit(Request $request)
    {
        return view('admin.moderators.edit', [
            'moderator' => $this->moderatorsService->get($request->post('id')),
            'menuItem' => 'moderatorslist'
        ]);
    }

    public function editSave(Request $request)
    {
        $this->moderatorsService->edit($request->input());
        return redirect()->to(route('moderators-list'));
    }

    public function delete(Request $request)
    {
        $this->moderatorsService->delete($request->post('id'));
        return redirect()->to(route('moderators-list'));
    }

    public function permissionsSave(Request $request)
    {
        $permissions = $request->input();
        unset($permissions['_token']);

        try {
            DB::beginTransaction();

            DB::table('admin_has_permissions')->truncate();

            foreach ($permissions as $key => $perm) {
                $up = explode('_', $key);

                $moderator = Admin::find($up['0']);
                $moderator->permissions()->attach($up[1]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->to(route('moderators-permissions'));
    }

}
