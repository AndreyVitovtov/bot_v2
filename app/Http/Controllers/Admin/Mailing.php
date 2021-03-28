<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Mailing extends Controller
{
    public function index()
    {
        if (file_exists(public_path() . "/json/mailing_task.json")) {
            $task = file_get_contents(public_path() . "/json/mailing_task.json");
            $task = json_decode($task, true);
            $disable = "disabled";
        } else {
            $task = "";
            $disable = "";
        }

        $res = DB::select("SELECT country FROM users WHERE country != '' GROUP BY country");
        $countries = [];
        if (App::getLocale() == "ru") {
            $ISO_3166_1_alpha_2 = json_decode(file_get_contents(public_path() . "/json/ISO_3166-1_alpha-2.json"), true);
        } else {
            $ISO_3166_1_alpha_2 = json_decode(file_get_contents(public_path() . "/json/ISO_3166-1_alpha-2_us.json"), true);
        }
        foreach ($res as $c) {
            $countries[$c->country] = $ISO_3166_1_alpha_2[$c->country];
        }

        return view('admin.mailing.mailing', [
            'task' => $task,
            'disable' => $disable,
            'countries' => $countries,
            'menuItem' => 'mailing'
        ]);
    }

    public function send(Request $request)
    {
        $params = $request->input();

        $task = [];
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . $file->getClientOriginalName();
            $file->move(public_path('\img\mailing'), $fileName);
            $task['type'] = "img";
            $task['img'] = url('/') . "/img/mailing/" . $fileName;
            $task['text'] = empty($params['text']) ? null : $params['text'];
        } else {
            if (!empty($params['url_image'])) {
                $task['type'] = "img";
                $task['img'] = $params['url_image'];
                $task['text'] = empty($params['text']) ? null : $params['text'];
            } else {
                if (empty($params['text'])) {
                    return redirect()->to("/admin/mailing");
                }
                $task['type'] = "text";
                $task['text'] = $params['text'];
            }
        }

        $db = DB::table('users')
            ->join('messengers', 'users.messengers_id', '=', 'messengers.id')
            ->where('messengers.name', 'LIKE', $params['messenger']);

        if ($params['country'] !== 'all') {
            $db = $db->where('country', $params['country']);
        }

        $count = $db->count();

        if ($count == 0) {
            return redirect()->to(route('mailing'));
        }

        $task['count'] = $count;
        $task['start'] = 0;
        $task['create'] = date("Y-m-d H:i:s");
        $task['performed'] = "false";
        $task['country'] = $params['country'];
        $task['messenger'] = $params['messenger'];

        file_put_contents(public_path('/json/mailing_task.json'), json_encode($task));
        file_put_contents(public_path('/txt/log.txt'), "");

        return redirect()->to(route('mailing'));
    }

    public function cancel()
    {
        $task = file_get_contents(public_path('/json/mailing_task.json'));
        $task = json_decode($task);
        if (isset($task->img)) {
            $imgArr = explode("/", $task->img);
            $imgName = end($imgArr);
            unlink(public_path('/img/mailing/' . $imgName));
        }
        unlink(public_path('/json/mailing_task.json'));
        return redirect()->to(route('mailing'));
    }

    public function analize()
    {
        $all = 0;
        $true = 0;
        $false = 0;

        $fileLog = fopen(public_path("/txt/log.txt"), "r");
        while (!feof($fileLog)) {
            $line = fgets($fileLog);
            if ($line != "") {
                $all++;
                $arrLine = explode("=>", $line);
                if (isset($arrLine[1])) {
                    $arr = json_decode($arrLine[1], true);
                    if (stripos($arrLine[0], "=") !== false) {
                        if ($arr['status'] == "0") {
                            $true++;
                        } else {
                            $false++;
                        }
                    } elseif (isset($arr['ok'])) {
                        if ($arr['ok'] == "true") {
                            $true++;
                        } else {
                            $false++;
                        }
                    } else {
                        if (isset($arr['error'])) {
                            $false++;
                        } else {
                            $true++;
                        }
                    }
                }
            }
        }
        fclose($fileLog);

        return view('admin.mailing.mailing-analize', [
            'data' => [
                'all' => $all,
                'true' => $true,
                'false' => $false
            ],
            'menuItem' => 'mailing'
        ]);
    }

    public function log()
    {
        if (file_exists(public_path() . "/txt/log.txt")) {
            $lines = file(public_path() . "/txt/log.txt");
            $log = '';
            foreach ($lines as $line) {
                $line = str_replace("=>", "<i class='icon-right-small'></i>", $line);
                $line = preg_replace('|[\s]+|s', ' ', $line);
                $log .= $line . "<br><br>";

            }
        } else {
            $log = "";
        }
        return view('admin.mailing.mailing-log', [
            'log' => $log,
            'menuItem' => 'mailing'
        ]);
    }

    public function markInactiveUsers()
    {
        $fileLog = fopen(public_path("/txt/log.txt"), "r");
        $count = 0;
        $ids = [];
        while (!feof($fileLog)) {
            $line = fgets($fileLog);
            if ($line != "") {
                $arrLine = explode("=>", $line);
                if (isset($arrLine[1])) {
                    $arr = json_decode($arrLine[1], true);

                    if (stripos($arrLine[0], "=") !== false) {
                        if ($arr['status'] != "0") {
                            $count++;
                            $ids[] = trim($arrLine[0]);
                        }
                    } elseif (isset($arr['ok'])) {
                        if ($arr['ok'] != "true") {
                            $count++;
                            $ids[] = trim($arrLine[0]);
                        }
                    }
                }
            }
        }

        $ids = array_chunk($ids, 50000);
        foreach ($ids as $key => $id) {
            DB::table('users')->whereIn('chat', $ids[$key])->update(['active' => 0]);
        }

        return redirect()->to(route('mailing-analize'));
    }
}
