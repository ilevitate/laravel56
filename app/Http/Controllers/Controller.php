<?php

namespace App\Http\Controllers;

use App\Models\OneWord;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

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


    public function oneWord(Request $request)
    {
        $rules = [
            'id' => 'required',
            'hitokoto' => 'required',
            'type' => 'required',
            'from' => 'required',
            'creator' => 'required',
            'created_at' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return $this->error();
        }
        $data = [
            'hitokoto_id' => $hitokoto_id = $request->get('id'),
            'hitokoto' => $request->get('hitokoto'),
            'type' => $request->get('type'),
            'from' => $request->get('from'),
            'creator' => $request->get('creator'),
            'create_time' => $request->get('created_at'),
            'created_at' => Carbon::now()
        ];

        $hitokoto = OneWord::where('hitokoto_id', $data['hitokoto_id'])->first();
        if (!$hitokoto){
            $hitokoto = OneWord::create($data);
        }

        return $this->success($hitokoto);
    }
}
