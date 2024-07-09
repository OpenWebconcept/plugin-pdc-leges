<?php

namespace OWC\PDC\Leges\Metabox;

use OWC\PDC\Base\Foundation\ServiceProvider;

class MetaboxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->plugin->loader->addFilter('rwmb_meta_boxes', $this, 'registerMetaboxes', 10, 1);
    }

    public function registerMetaboxes(array $metaboxes): array
    {

        $prefix = '_pdc-lege';

        $metaboxes[] = [
            'id' => 'pdc-leges',
            'title' => __('Lege settings', 'pdc-leges'),
            'post_types' => ['pdc-leges'],
            'context' => 'normal',
            'priority' => 'high',
            'autosave' => true,
            'fields' => [
                [
                    'id' => "{$prefix}-price",
                    'name' => __('Lege price', 'pdc-leges'),
                    'desc' => __('Price in &euro;', 'pdc-leges'),
                    'type' => 'text',
                ],
                [
                    'id' => "{$prefix}-new-price",
                    'name' => __('Lege new price', 'pdc-leges'),
                    'desc' => __('Price in &euro;', 'pdc-leges'),
                    'type' => 'text',
                ],
                [
                    'id' => "{$prefix}-active-date",
                    'name' => esc_html__('Date new lege active', 'pdc-leges'),
                    'type' => 'date',
                    'js_options' => [
                        'dateFormat' => esc_html__('dd-mm-yy', 'pdc-leges'),
                        'altFormat' => 'yy-mm-dd',
                        'changeMonth' => true,
                        'changeYear' => true,
                        'showButtonPanel' => true,
                        'minDate' => 0,
                    ],
                    'desc' => esc_html__('(dd-mm-yy)', 'pdc-leges'),
                ],
            ],
        ];

        return $metaboxes;
    }
}
