<?php

return [
    'pdc_leges' => [
        'id' => '_owc_pdc_leges_base_settings',
        'title' => __('PDC leges', 'pdc-leges'),
        'object_types' => ['options-page'],
        'option_key' => '_owc_pdc_leges_base_settings',
        'tab_group' => 'owc_pdc_leges',
        'tab_title' => __('General', 'pdc-leges'),
        'position' => 5,
        'icon_url' => 'dashicons-admin-settings',
        'fields' => [
            'pdc_leges_extended' => [
                'name' => __('Extend', 'pdc-leges'),
                'desc' => __('Extend the registered metaboxes with the following features: a person count threshold, an exception price, and a days selector for when a lege is applicable.', 'pdc-leges'),
                'id' => 'pdc_leges_setting_extended',
                'type' => 'checkbox',
            ]
        ],
    ],
];
