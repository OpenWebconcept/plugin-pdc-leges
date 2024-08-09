<?php

return [
    /**
     * Service Providers.
     */
    'providers' => [
        /**
         * Global providers.
         */
        OWC\PDC\Leges\PostType\LegesPostTypeServiceProvider::class,
        OWC\PDC\Leges\Shortcode\ShortcodeServiceProvider::class,
        OWC\PDC\Leges\RestAPI\RestAPIServiceProvider::class,
        OWC\PDC\Leges\WPCron\WPCronServiceProvider::class,
        OWC\PDC\Leges\Settings\SettingsServiceProvider::class,

        /**
         * Providers specific to the admin.
         */
        'admin' => [
            OWC\PDC\Leges\Admin\QuickEdit\QuickEditServiceProvider::class,
            OWC\PDC\Leges\Metabox\MetaboxServiceProvider::class,
        ],
    ],

    /**
     * The depedency checker, which is located in and executed by the pdc-base plugin, has been replaced.
     * For more details, see /includes/dependency-check.php.
     */
    'dependencies' => [],
];
