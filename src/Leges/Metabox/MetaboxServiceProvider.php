<?php

namespace OWC\PDC\Leges\Metabox;

use OWC\PDC\Base\Foundation\ServiceProvider;

use OWC\PDC\Leges\Traits\FloatSanitizer;
use OWC\PDC\Leges\Traits\WeekDays;

class MetaboxServiceProvider extends ServiceProvider
{
    use FloatSanitizer;
    use WeekDays;

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
            'attributes' => [
                'type' => 'number',
                'step' => '0.01',
            ],
            'sanitization_cb' => [$this, 'sanitizeFloat'],
        ]);

        $cmb->add_field([
            'name' => __('Lege new price', 'pdc-leges'),
            'desc' => __('Price in &euro;', 'pdc-leges'),
            'id' => "{$prefix}-new-price",
            'type' => 'text',
            'attributes' => [
                'type' => 'number',
                'step' => '0.01',
            ],
            'sanitization_cb' => [$this, 'sanitizeFloat'],
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

        $cmb->add_field([
            'name' => __('Start Time', 'pdc-leges'),
            'id' => "{$prefix}-start-time",
            'type' => 'text_time',
            'time_format' => 'H:i',
            'attributes' => [
                'data-time-format' => 'H:i',
            ],
        ]);

        $cmb->add_field([
            'name' => __('End Time', 'pdc-leges'),
            'id' => "{$prefix}-end-time",
            'type' => 'text_time',
            'time_format' => 'H:i',
            'attributes' => [
                'data-time-format' => 'H:i',
            ],
        ]);

        $cmb->add_field([
            'name' => __('Person Count Threshold', 'pdc-leges'),
            'desc' => __('Number of persons from which the price exception applies', 'pdc-leges'),
            'id' => "{$prefix}-person-count-threshold",
            'type' => 'text_small',
        ]);

        $cmb->add_field([
            'name' => __('Exception Price', 'pdc-leges'),
            'desc' => __('Price in &euro; when the person count threshold is met', 'pdc-leges'),
            'id' => "{$prefix}-exception-price",
            'type' => 'text',
            'attributes' => [
                'type' => 'number',
                'step' => '0.01',
            ],
            'sanitization_cb' => [$this, 'sanitizeFloat'],
        ]);

        $cmb->add_field([
            'name' => __('Applicable Days', 'pdc-leges'),
            'desc' => __('Select the days on which this lege is applicable', 'pdc-leges'),
            'id' => "{$prefix}-applicable-days",
            'type' => 'multicheck',
            'options' => $this->getWeekDays(),
        ]);
    }
}
