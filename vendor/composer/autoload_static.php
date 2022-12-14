<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd145404fbb773a3cdd1b6c2b4fffa395
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Shieldforce\\GoogleSearch\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Shieldforce\\GoogleSearch\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd145404fbb773a3cdd1b6c2b4fffa395::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd145404fbb773a3cdd1b6c2b4fffa395::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd145404fbb773a3cdd1b6c2b4fffa395::$classMap;

        }, null, ClassLoader::class);
    }
}
