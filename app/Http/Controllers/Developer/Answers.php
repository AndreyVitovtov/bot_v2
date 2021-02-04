<?php


namespace App\Http\Controllers\Developer;


use App\Http\Controllers\Controller;
use App\Http\Requests\AddAnswerRequest;
use App\Models\Answer;
use Illuminate\Http\Request;

class Answers extends Controller {
    public function index() {
        $view = view('developer.answers.answers');
        $view->answers = Answer::all();
        $view->menuItem = "answers";
        $menus = scandir(public_path('json/menu'));
        unset($menus[0]);
        unset($menus[1]);
        $view->menus = $menus;
        return $view;
    }

    public function add(Request $request) {
        $answer = new Answer();
        $answer->question = $request->post('question');
        $answer->answer = $request->post('answer');
        $answer->method = $request->post('method');
        if($request->post('menu') != 'null') {
            $answer->menu = $request->post('menu');
        }
        $answer->save();

        file_put_contents(public_path("json/answers.json"), Answer::all('question', 'answer', 'method')->toJson());

        return redirect()->route('index-answers');
    }

    public function edit(Request $request) {
        $view = view('developer.answers.edit-answer');
        $view->answer = Answer::find($request->post('id'));
        $view->menuItem = "answers";
        $menus = scandir(public_path('json/menu'));
        unset($menus[0]);
        unset($menus[1]);
        $view->menus = $menus;
        return $view;
    }

    public function save(Request $request) {
        $answer = Answer::find($request->post('id'));
        $answer->update([
            'question' => $request->post('question'),
            'answer' => $request->post('answer'),
            'method' => $request->post('method'),
            'menu' => ($request->post('menu') != 'null') ? $request->post('menu') : null
        ]);

        file_put_contents(public_path("json/answers.json"), Answer::all('question', 'answer', 'method')->toJson());

        return redirect()->route('index-answers');
    }

    public function delete(Request $request) {
        Answer::where('id', $request->post('id'))->delete();
        file_put_contents(public_path("json/answers.json"), Answer::all('question', 'answer', 'method')->toJson());

        return redirect()->route('index-answers');
    }
}
