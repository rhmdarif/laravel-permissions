<?php

use App\Models\WebConfig;
use App\Helpers\LogHelpers;
use App\Models\BotScenario;
use Illuminate\Support\Facades\DB;
use App\Models\BotCampaignScenario;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Lib\WhatsappController;

if (!function_exists("current_agent")) {
    function current_agent()
    {
        if(auth()->check()) return auth()->user();

        $token = PersonalAccessToken::findToken(Session::get("agent.token"));
        if ($token != null) return $token->tokenable;

        return null;
    }
}
if (!function_exists("get_number_jid")) {
    function get_number_jid($jid)
    {
        if (preg_match("/:/", $jid)) return explode(":", $jid)[0];
        if (preg_match("/@/", $jid)) return explode("@", $jid)[0];

        return $jid;
    }
}
if (!function_exists("phone_enam_dua")) {
    function phone_enam_dua($number)
    {
        $number = (int) get_number_jid($number);
        if (substr($number, 0, 1) == "8") $number = "62" . ((int) $number);
        return $number;
    }
}

if (!function_exists("send_to_webhook")) {
    function send_to_webhook($datas)
    {
        $validator = Validator::make($datas, [
            'webhook_url' => 'required|url',
            'datas' => 'required'
        ]);
        if ($validator->fails()) return false;

        #LogHelpers::store("helpersFunction", __FUNCTION__, __LINE__, $datas['webhook_url'], $datas);

        $datas['datas']['timeStamp'] = time();
        Http::post($datas['webhook_url'], $datas['datas']);
        return true;
    }
}

if (!function_exists("rename_class")) {
    function rename_class($class)
    {
        return str_replace("\\", "/", $class);
    }
}

if(!function_exists('web_config')) {
    function web_config($key)
    {
        if(!Session::has('web-config')) {
            $webconfigs = WebConfig::all();
            // dd($webconfigs);
            foreach ($webconfigs as $webconfig) {
                Session::put('web-config.'.$webconfig->key, $webconfig->value);
            }
        }
        return Session::get('web-config.'.$key, null);
    }
}

if(!function_exists('file_upload_exists')) {
    function file_upload_exists($file_path)
    {
        if(Storage::exists($file_path)) {
            Storage::delete($file_path);
        }

        return true;
    }
}


if(!function_exists('show_img')) {
    function show_img($file_path="", $type='lebar')
    {

        $file_path = (is_string($file_path))? $file_path : "";

        if(!empty($file_path) && Storage::exists($file_path)) {
            return Storage::url($file_path);
        }
        if(!empty($file_path) && file_exists(public_path($file_path))) {
            return asset($file_path);
        }
        if (filter_var($file_path, FILTER_VALIDATE_URL)) {
            return $file_path;
        }

        if(empty($file_path) || (!Storage::exists($file_path) AND !file_exists(public_path($file_path)))) {
            if ($type == "lebar") {
                $file_path = web_config("img.default-lebar") ?? "/assets/img/default/lebar.png";
            } else if ($type == "persegi") {
                $file_path = web_config("img.default-persegi") ?? "/assets/img/default/persegi.jpg";
            } else if ($type == "footer") {
                $file_path = web_config("img.default-footer") ?? "/assets/img/default/footer.png";
            }
        }


        return $file_path;
    }
}

if(!function_exists('label_config')) {
    function label_config($key)
    {
        $labels = [
            'msg.bot.section.not-found' => "Bot Section Not Found",
            'msg.bot.campaign.not-found' => "Bot Campaign Not Found",
            'msg.bot.autoreply.type' => "Autoreply Type",
            'msg.bot.autoreply.template' => "Autoreply Template",
            'msg.bot.campaign.end' => "Message when Bot Campaign End",
            'msg.bot.campaign.wrong-format' => "Message when Bot Campaign Wrong Format",
            'msg.bot.campaign.webhook' => "Message when Bot Campaign send to Webhook",
            'msg.bot.campaign.too-many-wrong' => "Message when Bot Campaign Too Many Wrong Format",
            'msg.bot.section.command-not-found' => "Bot Section Command Not Found",
            'msg.chat.out-of-operational-time' => "Chat Out Of Operational Time",
            'msg.chat.close' => "Chat closed by agent",
        ];

        return $labels[$key] ?? null;
    }
}

if(!function_exists('get_config')) {
    function get_config($key, $company_id)
    {
        $companyConfig = DB::table('company_configs')->where('company_id', $company_id)->where('key', $key)->first();
        if($companyConfig != null) {
            return $companyConfig->value;
        }

        return null;
    }
}
if(!function_exists('get_search_text')) {
    function get_search_text()
    {
        $request = request();
        $search = "";
        if($request->has('q')) $search = $request->q;

        return $search;
    }
}
if(!function_exists('search_records')) {
    function search_records($model, $fields=[], $search="")
    {
        if(count($fields) == 0 || empty($search)) return $model;

        foreach ($fields as $field) {
            $wheres[] = [$field, 'like', '%' . $search . '%'];
        }

        return $model->where($wheres);
    }
}


if(!function_exists('format_ajax_select2')) {
    function format_ajax_select2($modelSimplePaginate)
    {
        $morePages = true;
            // $pagination_obj = json_encode($modelSimplePaginate);
            if (empty($modelSimplePaginate->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $modelSimplePaginate->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );

        return $results;
    }
}


if(!function_exists('autorows_col')) {
    function autorows_col($query)
    {
        $count = $query->count();
        if($count % 4 == 0) {
            return "col-md-3";
        }
        if($count % 3 == 0) {
            return "col-md-4";
        }
        if($count % 2 == 0) {
            return 2;
        }
    }
}

