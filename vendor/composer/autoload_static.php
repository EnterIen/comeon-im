<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8f3411af7a7a58e545cca1dc386d93c8
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Workerman\\' => 10,
        ),
        'G' => 
        array (
            'GatewayWorker\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Workerman\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/workerman',
        ),
        'GatewayWorker\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/gateway-worker/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8f3411af7a7a58e545cca1dc386d93c8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8f3411af7a7a58e545cca1dc386d93c8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8f3411af7a7a58e545cca1dc386d93c8::$classMap;

        }, null, ClassLoader::class);
    }
}
