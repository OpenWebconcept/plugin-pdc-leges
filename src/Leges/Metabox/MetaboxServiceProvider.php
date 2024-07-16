<?php

namespace OWC\PDC\Leges\Metabox;

use OWC\PDC\Base\Foundation\ServiceProvider;

class MetaboxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        add_filter('cmb2_admin_init', [$this, 'registerMetaboxes'], 10, 0);
    }

    public function registerMetaboxes(): void
    {
        $prefix = '_pdc-lege';

        $cmb = new_cmb2_box([
            'id' => 'pdc_leges',
            'title' => __('Lege settings', 'pdc-leges'),
            'object_types' => ['pdc-leges'],
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true,
        ]);

        $cmb->add_field([
            'name' => __('Lege price', 'pdc-leges'),
            'desc' => __('Price in &euro;', 'pdc-leges'),
            'id' => "{$prefix}-price",
            'type' => 'text',
        ]);

        $cmb->add_field([
            'name' => __('Lege new price', 'pdc-leges'),
            'desc' => __('Price in &euro;', 'pdc-leges'),
            'id' => "{$prefix}-new-price",
            'type' => 'text',
        ]);

        $cmb->add_field([
            'name' => esc_html__('Date new lege active', 'pdc-leges'),
            'id' => "{$prefix}-active-date",
            'type' => 'text_date',
            'date_format' => 'd-m-Y',
            'attributes' => [
                'data-date-format' => esc_html__('dd-mm-yy', 'pdc-leges'),
                'data-alt-format' => 'yy-mm-dd',
                'data-change-month' => true,
                'data-change-year' => true,
                'data-show-button-panel' => true,
                'data-min-date' => 0,
            ],
            'desc' => esc_html__('(dd-mm-yy)', 'pdc-leges'),
        ]);
    }
}
