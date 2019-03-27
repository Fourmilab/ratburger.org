<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3f8da02c8fe4fc53ceb94aa6d8d76a7b
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twig\\' => 5,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Ctype\\' => 23,
        ),
        'P' => 
        array (
            'Psr\\Container\\' => 14,
        ),
        'I' => 
        array (
            'Interop\\Container\\' => 18,
        ),
        'D' => 
        array (
            'Dhii\\Stats\\' => 11,
            'Dhii\\Di\\' => 8,
            'Dhii\\Collection\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Interop\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/container-interop/container-interop/src/Interop/Container',
            1 => __DIR__ . '/..' . '/container-interop/service-provider/src',
        ),
        'Dhii\\Stats\\' => 
        array (
            0 => __DIR__ . '/..' . '/dhii/stats-interface/src',
            1 => __DIR__ . '/..' . '/dhii/stats-abstract/src',
        ),
        'Dhii\\Di\\' => 
        array (
            0 => __DIR__ . '/..' . '/dhii/di-interface/src',
            1 => __DIR__ . '/..' . '/dhii/di-abstract/src',
            2 => __DIR__ . '/..' . '/dhii/di/src',
        ),
        'Dhii\\Collection\\' => 
        array (
            0 => __DIR__ . '/..' . '/dhii/collections-interface/src',
            1 => __DIR__ . '/..' . '/dhii/collections-abstract-base/src',
            2 => __DIR__ . '/..' . '/dhii/collections-abstract/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3f8da02c8fe4fc53ceb94aa6d8d76a7b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3f8da02c8fe4fc53ceb94aa6d8d76a7b::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit3f8da02c8fe4fc53ceb94aa6d8d76a7b::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
