<?php

namespace MrStock\System\Helper;

use MrStock\System\MJC\Facade\Debug;
use MrStock\System\MJC\Facade\Log;
use MrStock\System\MJC\Log\LogLevel;
use MrStock\System\MJC\Container;

class Output
{

    /**
     * 格式化返回数据
     *
     * @param number $code
     * @param unknown $data
     * @return string[]|number[]|unknown[]|array[]
     */
    protected static function format($data, $code = 1)
    {
        $result = array();

        $result['code'] = $code;
        if (intval($code) <= 0) {
            $result['message'] = $data;
        } else {
            $result['message'] = "ok";

            $result['data'] = $data;
        }
        $request = Container::get('request');
        if ($request->isdebug) {
            $debugData = Debug::getData();
            if ($debugData) {
                $result['debug'] = $debugData;
            }
        }

        $log = json_encode($result);
        Log::write($code, LogLevel::ACCESS);
        Log::write($log, LogLevel::ROUTERECORD);

        if (intval($code) <= 0) {
            if ($code == -404) {
                Log::write($log, LogLevel::ROUTENONE);
            } elseif ($code == -500) {
                Log::write($log, LogLevel::ERR);
            }
        }
        return Tool::_unsetNull($result);
    }

    public static function response($data, $code = 1, $status = 200)
    {
        $request = Container::get("request");

        if ($code < 0 && !in_array(substr($code, -5), array_values(Config::get("rpcerrorcode")))) {
            $rpcerrorcode = Config::get("rpcerrorcode." . $request->param["site"]);
            $code = $code . $rpcerrorcode;
        }

        $response = Container::get("response");

        $data = self::format($data, $code);
        return $response->data($data)->code($status);
    }

}