<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8b6b1cc8c15ad32797fe7fe35890da42
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'wk00FF\\UsersHelper' => __DIR__ . '/../..' . '/src/UsersHelper.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit8b6b1cc8c15ad32797fe7fe35890da42::$classMap;

        }, null, ClassLoader::class);
    }
}
