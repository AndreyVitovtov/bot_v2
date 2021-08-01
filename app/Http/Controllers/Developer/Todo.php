<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\TodoModel;
use Illuminate\Http\Request;

/**
 * @method static find(mixed $id)
 */
class Todo extends Controller
{
    public function index()
    {
        return view('developer.todo.index', [
            'todo' => TodoModel::with('status')->get(),
            'menuItem' => 'todo'
        ]);
    }

    public function add(Request $request)
    {
        $todo = new TodoModel();
        $todo->title = $request['title'];
        $todo->status = 1;
        $todo->datetime = date('Y-m-d H:i:s');
        $todo->save();
        return redirect()->to(route('todo'));
    }

    public function toMake(Request $request) {
        TodoModel::where('id', $request['id'])->update(['status' => 1]);
        return redirect()->to(route('todo'));
    }

    public function toWork(Request $request)
    {
        TodoModel::where('id', $request['id'])->update(['status' => 2]);
        return redirect()->to(route('todo'));
    }

    public function toPerformed(Request $request)
    {
        TodoModel::where('id', $request['id'])->update(['status' => 3]);
        return redirect()->to(route('todo'));
    }

    public function delete(Request $request)
    {
        TodoModel::where('id', $request['id'])->delete();
        return redirect()->to(route('todo'));
    }
}
