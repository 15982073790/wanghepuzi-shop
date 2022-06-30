<?php

namespace MrStock\System\Helper;

use MrStock\System\MJC\Facade\Log;
use MrStock\System\MJC\Log\LogLevel;

class CliHelper
{

    /**
     * 命令行开始
     */
    public static function cliStart()
    {
        if (ob_get_level()) ob_end_clean();
    }

    /**
     * @param $message
     * 命令行输出文字
     */
    public static function cliEcho($message)
    {
        fwrite(STDOUT, $message . PHP_EOL);
        Log::write($message, LogLevel::CLIECHO);
        self::cliFlush();
    }

    /**
     * 命令行 休眠并输出
     * @param $seconds
     */
    public static function cliSleep($seconds)
    {
        $message = 'Sleep ' . $seconds . 'S';
        self::cliEcho($message);
        sleep($seconds);
    }

    /**
     * 命令行 休眠并输出
     * @param $seconds
     */
    public static function cliUsleep($micro_second)
    {
        $message = 'Usleep ' . $micro_second . ' micro_second';
        self::cliEcho($message);
        usleep($micro_second);
    }


    /**
     * 命令行 及时输出
     */
    public static function cliFlush()
    {
        flush();
        ob_flush();
    }

}