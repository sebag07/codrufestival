<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfc85d332cbf70b74cecfa55e2252b8ba
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'OTGS_Assets_Handles' => __DIR__ . '/..' . '/otgs/ui/src/php/OTGS_Assets_Handles.php',
        'OTGS_Assets_Store' => __DIR__ . '/..' . '/otgs/ui/src/php/OTGS_Assets_Store.php',
        'OTGS_UI_Assets' => __DIR__ . '/..' . '/otgs/ui/src/php/OTGS_UI_Assets.php',
        'OTGS_UI_Loader' => __DIR__ . '/..' . '/otgs/ui/src/php/OTGS_UI_Loader.php',
        'WPML\\Forms\\Addons\\WpForms\\SaveAndResume' => __DIR__ . '/../..' . '/classes/Addons/WpForms/SaveAndResume.php',
        'WPML\\Forms\\Addons\\WpForms\\SurveyAndPolls' => __DIR__ . '/../..' . '/classes/Addons/WpForms/SurveyAndPolls.php',
        'WPML\\Forms\\Hooks\\WpForms\\ConversationalForms' => __DIR__ . '/../..' . '/classes/Hooks/WpForms/ConversationalForms.php',
        'WPML\\Forms\\Hooks\\WpForms\\DynamicChoices' => __DIR__ . '/../..' . '/classes/Hooks/WpForms/DynamicChoices.php',
        'WPML\\Forms\\Hooks\\WpForms\\EntryPreviewField' => __DIR__ . '/../..' . '/classes/Hooks/WpForms/EntryPreviewField.php',
        'WPML\\Forms\\Hooks\\WpForms\\FormPages' => __DIR__ . '/../..' . '/classes/Hooks/WpForms/FormPages.php',
        'WPML\\Forms\\Hooks\\WpForms\\Notifications' => __DIR__ . '/../..' . '/classes/Hooks/WpForms/Notifications.php',
        'WPML\\Forms\\Hooks\\WpForms\\Package' => __DIR__ . '/../..' . '/classes/Hooks/WpForms/Package.php',
        'WPML\\Forms\\Hooks\\WpForms\\Strings' => __DIR__ . '/../..' . '/classes/Hooks/WpForms/Strings.php',
        'WPML\\Forms\\Loader\\WpForms' => __DIR__ . '/../..' . '/classes/Loader/WpForms.php',
        'WPML\\Forms\\Loader\\WpFormsStatus' => __DIR__ . '/../..' . '/classes/Loader/WpFormsStatus.php',
        'WPML\\Forms\\WpForms\\SmartTag' => __DIR__ . '/../..' . '/classes/WpForms/SmartTag.php',
        'WPML_Core_Version_Check' => __DIR__ . '/..' . '/wpml-shared/wpml-lib-dependencies/src/dependencies/class-wpml-core-version-check.php',
        'WPML_Dependencies' => __DIR__ . '/..' . '/wpml-shared/wpml-lib-dependencies/src/dependencies/class-wpml-dependencies.php',
        'WPML_PHP_Version_Check' => __DIR__ . '/..' . '/wpml-shared/wpml-lib-dependencies/src/dependencies/class-wpml-php-version-check.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitfc85d332cbf70b74cecfa55e2252b8ba::$classMap;

        }, null, ClassLoader::class);
    }
}
