<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\TodoModel;
use Illuminate\Http\Request;

class Todo extends Controller
{
    public function index()
    {
        return view('developer.todo.index', [
            'todo' => TodoModel::all(),
            'menuItem' => 'todo'
        ]);
    }
}
