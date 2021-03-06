<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb6e06f1966ba2beb8cd8739628364cc2
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/Twilio',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb6e06f1966ba2beb8cd8739628364cc2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb6e06f1966ba2beb8cd8739628364cc2::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
