<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2499ee4daf69eb0ad6d2706ccf58a54b
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'b067bc7112e384b61c701452d53a14a8' => __DIR__ . '/..' . '/mtdowling/jmespath.php/src/JmesPath.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
        '65fec9ebcfbb3cbb4fd0d519687aea01' => __DIR__ . '/..' . '/danielstjules/stringy/src/Create.php',
        'd767e4fc2dc52fe66584ab8c6684783e' => __DIR__ . '/..' . '/adbario/php-dot-notation/src/helpers.php',
        '4e66b0302c5365a258da95ac48c898b4' => __DIR__ . '/..' . '/alibabacloud/client/src/Constants/Business.php',
        '8edfd8536faf1ddbd3387572b4787747' => __DIR__ . '/..' . '/alibabacloud/client/src/Constants/ErrorCode.php',
        '66453932bc1be9fb2f910a27947d11b6' => __DIR__ . '/..' . '/alibabacloud/client/src/Functions.php',
        'cd504dd38cbb5730802cddd1e32ff434' => __DIR__ . '/..' . '/alibabacloud/client/src/Load.php',
        '63a61fd025a31affebb0723ca3298418' => __DIR__ . '/..' . '/alibabacloud/sdk/src/Constants.php',
    );

    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'clagiordano\\weblibs\\configmanager\\' => 34,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Stringy\\' => 8,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
        'J' => 
        array (
            'JmesPath\\' => 9,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
        'A' => 
        array (
            'AlibabaCloud\\Client\\' => 20,
            'AlibabaCloud\\' => 13,
            'Adbar\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'clagiordano\\weblibs\\configmanager\\' => 
        array (
            0 => __DIR__ . '/..' . '/clagiordano/weblibs-configmanager/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Stringy\\' => 
        array (
            0 => __DIR__ . '/..' . '/danielstjules/stringy/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'JmesPath\\' => 
        array (
            0 => __DIR__ . '/..' . '/mtdowling/jmespath.php/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'AlibabaCloud\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/alibabacloud/client/src',
        ),
        'AlibabaCloud\\' => 
        array (
            0 => __DIR__ . '/..' . '/alibabacloud/sdk/src',
        ),
        'Adbar\\' => 
        array (
            0 => __DIR__ . '/..' . '/adbario/php-dot-notation/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2499ee4daf69eb0ad6d2706ccf58a54b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2499ee4daf69eb0ad6d2706ccf58a54b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
