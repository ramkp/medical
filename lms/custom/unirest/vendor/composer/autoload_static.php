<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6f4acbb1cce55ac3a5f8395263e29123
{
    public static $prefixesPsr0 = array (
        'U' => 
        array (
            'Unirest\\' => 
            array (
                0 => __DIR__ . '/..' . '/mashape/unirest-php/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit6f4acbb1cce55ac3a5f8395263e29123::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
