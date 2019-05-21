<?php

namespace App\Http\Controllers;

use App\Models\OneWord;
use App\Utils\CurlUtil;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 调用成功
     * @param string $msg
     * @param array $data
     * @return array
     */
    function success($data = [], $msg = 'success')
    {
        header('WWW-Authenticate: xBasic realm=""');

        return ['code' => 200, 'msg' => $msg, 'data' => $data];
    }


    /**
     * 调用失败
     * @param string $msg
     * @param array $data
     * @return array
     */
    function error($msg = '参数格式错误', $data = [])
    {
        header('WWW-Authenticate: xBasic realm=""');

        return ['code' => 400, 'msg' => $msg, 'data' => $data];
    }


    public function oneWord()
    {
        $oneWordUrl = 'http://v1.hitokoto.cn';
        $response = CurlUtil::curl_request($oneWordUrl);
        $hitokoto =  json_decode($response, true);
        $this->oneWordToDB($hitokoto);
        return $this->success($hitokoto);
    }

    public function oneWordToDB($hitokoto)
    {
        $data = [
            'hitokoto_id' => $hitokoto['id'],
            'hitokoto' => $hitokoto['hitokoto'],
            'type' => $hitokoto['type'],
            'from' => $hitokoto['from'],
            'creator' => $hitokoto['creator'],
            'create_time' => time(),
            'created_at' => Carbon::now()
        ];

        $hitokoto = OneWord::where('hitokoto_id', $data['hitokoto_id'])->first();
        if (!$hitokoto){
            $hitokoto = OneWord::create($data);
        }
        return $hitokoto;
    }
}
