<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitec050f0ce163aebbb4cac8e0ec201651
{
    public static $files = array (
        'b45b351e6b6f7487d819961fef2fda77' => __DIR__ . '/..' . '/jakeasmith/http_build_url/src/http_build_url.php',
        '5f5b8f7c1db2e892006e8805f0ed573c' => __DIR__ . '/..' . '/wpml/collect/src/Illuminate/Support/helpers.php',
        'a7a427652651280ba7678b2706c00e7a' => __DIR__ . '/../..' . '/fp/core/functions.php',
        'b602584835b44b00c472df7ee8b179a4' => __DIR__ . '/../..' . '/fp/core/strings_functions.php',
        '76610cde2f17b5f7af6bd579c22f6303' => __DIR__ . '/../..' . '/fp/core/system.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPML\\Collect\\' => 13,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPML\\Collect\\' => 
        array (
            0 => __DIR__ . '/..' . '/wpml/collect/src/Illuminate',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'OTGS\\InstallerPlugin\\OtgsInstallerPlugin' => __DIR__ . '/../..' . '/includes/OtgsInstallerPlugin.php',
        'OTGS\\InstallerPlugin\\PluginDeactivator' => __DIR__ . '/../..' . '/includes/PluginDeactivator.php',
        'WPML\\INSTALLER\\FP\\Applicative' => __DIR__ . '/../..' . '/fp/core/Functor/Applicative.php',
        'WPML\\INSTALLER\\FP\\Cast' => __DIR__ . '/../..' . '/fp/core/Cast.php',
        'WPML\\INSTALLER\\FP\\ConstApplicative' => __DIR__ . '/../..' . '/fp/core/Functor/ConstApplicative.php',
        'WPML\\INSTALLER\\FP\\Curryable' => __DIR__ . '/../..' . '/fp/core/traits/Curryable.php',
        'WPML\\INSTALLER\\FP\\Debug' => __DIR__ . '/../..' . '/fp/core/Debug.php',
        'WPML\\INSTALLER\\FP\\Either' => __DIR__ . '/../..' . '/fp/core/Either.php',
        'WPML\\INSTALLER\\FP\\FP' => __DIR__ . '/../..' . '/fp/core/FP.php',
        'WPML\\INSTALLER\\FP\\Fns' => __DIR__ . '/../..' . '/fp/core/Fns.php',
        'WPML\\INSTALLER\\FP\\Functor\\ConstFunctor' => __DIR__ . '/../..' . '/fp/core/Functor/ConstFunctor.php',
        'WPML\\INSTALLER\\FP\\Functor\\Functor' => __DIR__ . '/../..' . '/fp/core/Functor/Functor.php',
        'WPML\\INSTALLER\\FP\\Functor\\IdentityFunctor' => __DIR__ . '/../..' . '/fp/core/Functor/IdentityFunctor.php',
        'WPML\\INSTALLER\\FP\\Functor\\Pointed' => __DIR__ . '/../..' . '/fp/core/Functor/Pointed.php',
        'WPML\\INSTALLER\\FP\\Json' => __DIR__ . '/../..' . '/fp/core/Json.php',
        'WPML\\INSTALLER\\FP\\Just' => __DIR__ . '/../..' . '/fp/core/Maybe.php',
        'WPML\\INSTALLER\\FP\\Left' => __DIR__ . '/../..' . '/fp/core/Either.php',
        'WPML\\INSTALLER\\FP\\Logic' => __DIR__ . '/../..' . '/fp/core/Logic.php',
        'WPML\\INSTALLER\\FP\\Lst' => __DIR__ . '/../..' . '/fp/core/Lst.php',
        'WPML\\INSTALLER\\FP\\Math' => __DIR__ . '/../..' . '/fp/core/Math.php',
        'WPML\\INSTALLER\\FP\\Maybe' => __DIR__ . '/../..' . '/fp/core/Maybe.php',
        'WPML\\INSTALLER\\FP\\Nothing' => __DIR__ . '/../..' . '/fp/core/Maybe.php',
        'WPML\\INSTALLER\\FP\\Obj' => __DIR__ . '/../..' . '/fp/core/Obj.php',
        'WPML\\INSTALLER\\FP\\Promise' => __DIR__ . '/../..' . '/fp/core/Promise.php',
        'WPML\\INSTALLER\\FP\\Relation' => __DIR__ . '/../..' . '/fp/core/Relation.php',
        'WPML\\INSTALLER\\FP\\Right' => __DIR__ . '/../..' . '/fp/core/Either.php',
        'WPML\\INSTALLER\\FP\\Str' => __DIR__ . '/../..' . '/fp/core/Strings.php',
        'WPML\\INSTALLER\\FP\\System\\System' => __DIR__ . '/../..' . '/fp/core/SystemClass.php',
        'WPML\\INSTALLER\\FP\\System\\_Filter' => __DIR__ . '/../..' . '/fp/core/Filter.php',
        'WPML\\INSTALLER\\FP\\System\\_Validator' => __DIR__ . '/../..' . '/fp/core/Validator.php',
        'WPML\\INSTALLER\\FP\\Undefined' => __DIR__ . '/../..' . '/fp/core/Undefined.php',
        'WPML\\INSTALLER\\FP\\Wrapper' => __DIR__ . '/../..' . '/fp/core/Wrapper.php',
        'WPML\\INSTALLER\\FP\\_Invoker' => __DIR__ . '/../..' . '/fp/core/Invoker.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitec050f0ce163aebbb4cac8e0ec201651::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitec050f0ce163aebbb4cac8e0ec201651::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitec050f0ce163aebbb4cac8e0ec201651::$classMap;

        }, null, ClassLoader::class);
    }
}