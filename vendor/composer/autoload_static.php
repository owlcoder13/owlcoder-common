<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2aa70607e3c66d1a08d21216d74ba22c
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Owlcoder\\Common\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Owlcoder\\Common\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2aa70607e3c66d1a08d21216d74ba22c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2aa70607e3c66d1a08d21216d74ba22c::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
