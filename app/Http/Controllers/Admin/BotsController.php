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

            $languages = $request->post('languages');
            foreach($languages as $language) {
                $bot->languages()->attach($language);
            }

            $messenger = Messenger::find($request['messenger']);
            $webhook = new Webhook();
            $res = json_decode($webhook->set([$messenger->name => true], $request['token'], $bot->id));

            if ((($res->ok ?? true) == false) || ($res->status ?? 0) != 0) {
                throw new Exception($res->status_message ?? $res->description ?? $res->status_message ??
                    $res->chat_hostname ?? $res->status);
            }

            DB::commit();
        } catch (Exception $e) {
            $message = $e->getMessage();
            DB::rollBack();
        }
        return redirect()->to(route('bots-list', [
            'message' => $res->status_message ?? $res->description ?? $message ?? $res->status ?? null
        ]));
    }

    public function delete(Request $request): RedirectResponse
    {
        Bot::where('id', $request['id'])->delete();
        return redirect()->to(route('bots-list'));
    }

    public function edit(Request $request)
    {
        $bot = Bot::find($request['id']);
        $languages = DB::select("
            SELECT `languages_id`
            FROM `bots_has_languages`
            WHERE `bots_id` = " . $bot->id . "
        ");
        foreach($languages as $language) {
            $botLanguages[] = $language->languages_id;
        }
        return view('admin.bots.edit', [
            'messengers' => Messenger::all(),
            'languages' => Language::all(),
            'bot' => $bot,
            'menuItem' => 'botslist',
            'botLanguages' => $botLanguages ?? []
        ]);
    }

    public function editSave(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $bot = Bot::find($request['id']);

            $token = $bot->token;
            $bot->languages_id = $request['language'];
            $bot->name = $request['name'];
            $bot->token = $request['token'];
            $bot->save();

            $bot->languages()->detach();
            $languages = $request->post('languages') ?? [];

            foreach($languages as $language) {
                $bot->languages()->attach($language);
            }

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
            $message = $e;
            DB::rollBack();
        }
        return redirect()->to(route('bots-list', [
            'message' => $res->status_message ?? $res->description ?? $message ?? null
        ]));
    }
}
