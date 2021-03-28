<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Http\Request;

class Answers extends Controller
{
    public function list()
    {
        return view('admin.answers.answers-list', [
            'answers' => Answer::all(),
            'menuItem' => 'answerslist'
        ]);
    }

    public function add()
    {
        return view('admin.answers.answers-add', [
            'menuItem' => 'answersadd'
        ]);
    }

    public function edit(Request $request)
    {
        $id = $request->input()['id'];
        if (empty($id)) return redirect()->to("/admin/answers/list");

        return view('admin.answers.answers-edit', [
            'answer' => Answer::find($id),
            'menuItem' => 'answerslist'
        ]);
    }

    public function save(Request $request)
    {
        $fills = $request->input();

        if (empty($fills['question']) || empty($fills['answer'])) {
            return redirect()->to("/admin/answers/add");
        }

        unset($fills['_token']);
        $answer = new Answer();

        if (isset($fills['id'])) {
            $answer::where('id', $fills['id'])
                ->update($fills);
        } else {
            $answer->fill($fills);
            $answer->save();
        }

        file_put_contents(public_path("json/answers.json"), Answer::all('question', 'answer', 'method')->toJson());

        return redirect()->to(route('answers'));
    }

    public function delete(Request $request)
    {
        $id = $request->input()['id'];
        if (empty($id)) return redirect()->to(route('answers'));

        Answer::where('id', $id)->delete();

        file_put_contents(public_path("json/answers.json"), Answer::all('question', 'answer', 'method')->toJson());

        return redirect()->to(route('answers'));
    }
}
