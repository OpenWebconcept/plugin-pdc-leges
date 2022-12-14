<?php
/**
 * Provider which handles the metabox registration.
 */

namespace OWC\PDC\Leges\Metabox;

use OWC\PDC\Base\Foundation\ServiceProvider;

/**
 * Provider which handles the metabox registration.
 */
class MetaboxServiceProvider extends ServiceProvider
{

    /**
     * Register the providers.
     *
     * @return void
     */
    public function register()
    {
        $this->plugin->loader->addFilter('rwmb_meta_boxes', $this, 'registerMetaboxes', 10, 1);
    }

    /**
     * Register metaboxes.
     *
     * @param array $metaboxes
     *
     * @return array
     */
    public function registerMetaboxes($metaboxes)
    {

        $prefix = '_pdc-lege';

        $metaboxes[] = [
            'id'         => 'pdc-leges',
            'title'      => __('Lege settings', 'pdc-leges'),
            'post_types' => ['pdc-leges'],
            'context'    => 'normal',
            'priority'   => 'high',
            'autosave'   => true,
            'fields'     => [
                [
                    'id'   => "{$prefix}-price",
                    'name' => __('Lege price', 'pdc-leges'),
                    'desc' => __('Price in &euro;', 'pdc-leges'),
                    'type' => 'text',
                ],
                [
                    'id'   => "{$prefix}-new-price",
                    'name' => __('Lege new price', 'pdc-leges'),
                    'desc' => __('Price in &euro;', 'pdc-leges'),
                    'type' => 'text',
                ],
                [
                    'id'         => "{$prefix}-active-date",
                    'name'       => esc_html__('Date new lege active', 'pdc-leges'),
                    'type'       => 'date',
                    'js_options' => [
                        'dateFormat'      => esc_html__('dd-mm-yy', 'pdc-leges'),
                        'altFormat'       => 'yy-mm-dd',
                        'changeMonth'     => true,
                        'changeYear'      => true,
                        'showButtonPanel' => true,
                        'minDate'         => 0
                    ],
                    'desc'       => esc_html__('(dd-mm-yy)', 'pdc-leges'),
                ]
            ]
        ];

        return $metaboxes;
    }
}
