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

        /**
         * Providers specific to the admin.
         */
        'admin' => [
            OWC\PDC\Leges\Admin\QuickEdit\QuickEditServiceProvider::class,
            OWC\PDC\Leges\Metabox\MetaboxServiceProvider::class,
        ],
    ],

    /**
     * Dependencies upon which the plugin relies.
     *
     * Should contain: label, version, file.
     */
    'dependencies' => [
        [
            'type' => 'plugin',
            'label' => 'OpenPDC Base',
            'version' => '2.1.5',
            'file' => 'pdc-base/pdc-base.php',
        ],
    ],
];
