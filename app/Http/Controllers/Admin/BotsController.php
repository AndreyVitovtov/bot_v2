<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\Language;
use App\Models\Messenger;
use App\Models\Webhook;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BotsController extends Controller
{
    public function list(Request $request)
    {
        return view('admin.bots.list', [
            'bots' => Bot::all(),
            'menuItem' => 'botslist',
            'message' => $request['message'] ?? null
        ]);
    }

    public function add()
    {
        return view('admin.bots.add', [
            'messengers' => Messenger::all(),
            'languages' => Language::all(),
            'menuItem' => 'botsadd'
        ]);
    }

    public function addSave(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $bot = new Bot();
            $bot->messengers_id = $request['messenger'];
            $bot->languages_id = $request['language'];
            $bot->name = $request['name'];
            $bot->token = $request['token'];
            $bot->save();

            $messenger = Messenger::find($request['messenger']);
            $webhook = new Webhook();
            $res = json_decode($webhook->set([$messenger->name => true], $request['token'], $bot->id));

            if ((($res->ok ?? true) == false) || ($res->status ?? 0) != 0) {
                throw new Exception($res->status_message ?? $res->description);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->to(route('bots-list', ['message' => $res->status_message ?? $res->description ?? null]));
    }

    public function delete(Request $request): RedirectResponse
    {
        Bot::where('id', $request['id'])->delete();
        return redirect()->to(route('bots-list'));
    }

    public function edit(Request $request)
    {
        return view('admin.bots.edit', [
            'messengers' => Messenger::all(),
            'languages' => Language::all(),
            'bot' => Bot::find($request['id']),
            'menuItem' => 'botslist'
        ]);
    }

    public function editSave(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $bot = Bot::find($request['id']);
            $token = $bot->token;

            $bot->name = $request['name'];
            $bot->token = $request['token'];
            $bot->save();

            if ($token != $bot->token) {
                $messenger = Messenger::find($request['messenger']);
                $webhook = new Webhook();
                $res = json_decode($webhook->set([$messenger->name => true], $request['token'], $bot->id));

                if ((($res->ok ?? true) == false) || ($res->status ?? 0) != 0) {
                    throw new Exception($res->status_message ?? $res->description);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->to(route('bots-list', ['message' => $res->status_message ?? $res->description ?? null]));
    }
}
