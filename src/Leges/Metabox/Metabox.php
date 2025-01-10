<?php

namespace OWC\PDC\Leges\Metabox;

use CMB2;
use OWC\PDC\Leges\Settings\Settings;
use OWC\PDC\Leges\Traits\NumberSanitizer;
use OWC\PDC\Leges\Traits\WeekDays;

class Metabox
{
    use NumberSanitizer;
    use WeekDays;

    public const PREFIX = '_pdc-lege';

    public function registerMetaboxes(): void
    {
        $cmb = new_cmb2_box([
            'id' => 'pdc_leges',
            'title' => __('Lege settings', 'pdc-leges'),
            'object_types' => ['pdc-leges'],
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true,
        ]);

        $this->addDefaultFields($cmb);

        if (! Settings::make()->extensionEnabled()) {
            return;
        }

        $this->addExtensionFields($cmb);
    }

    protected function addDefaultFields(CMB2 $cmb): void
    {
        $cmb->add_field([
            'name' => __('Lege price', 'pdc-leges'),
            'desc' => __('Price in &euro;', 'pdc-leges'),
            'id' => sprintf('%s-price', self::PREFIX),
            'type' => 'text',
            'sanitization_cb' => [$this, 'sanitizeFloat'],
        ]);

        $cmb->add_field([
            'name' => __('Lege new price', 'pdc-leges'),
            'desc' => __('Price in &euro;', 'pdc-leges'),
            'id' => sprintf('%s-new-price', self::PREFIX),
            'type' => 'text',
            'sanitization_cb' => [$this, 'sanitizeFloat'],
        ]);

        $cmb->add_field([
            'name' => esc_html__('Date new lege active', 'pdc-leges'),
            'id' => sprintf('%s-active-date', self::PREFIX),
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

    protected function addExtensionFields(CMB2 $cmb): void
    {
        $cmb->add_field([
            'name' => __('Start Time', 'pdc-leges'),
            'id' => sprintf('%s-start-time', self::PREFIX),
            'type' => 'text_time',
            'time_format' => 'H:i',
            'attributes' => [
                'data-time-format' => 'H:i',
            ],
        ]);

        $cmb->add_field([
            'name' => __('End Time', 'pdc-leges'),
            'id' => sprintf('%s-end-time', self::PREFIX),
            'type' => 'text_time',
            'time_format' => 'H:i',
            'attributes' => [
                'data-time-format' => 'H:i',
            ],
        ]);

        $cmb->add_field([
            'name' => __('Person Count Threshold', 'pdc-leges'),
            'desc' => __('Number of persons from which the price exception applies', 'pdc-leges'),
            'id' => sprintf('%s-person-count-threshold', self::PREFIX),
            'type' => 'text_small',
        ]);

        $cmb->add_field([
            'name' => __('Exception Price', 'pdc-leges'),
            'desc' => __('Price in &euro; when the person count threshold is met', 'pdc-leges'),
            'id' => sprintf('%s-exception-price', self::PREFIX),
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
            'id' => sprintf('%s-applicable-days', self::PREFIX),
            'type' => 'multicheck',
            'options' => $this->getWeekDays(),
        ]);

        /**
         * Allows adding custom CMB2 metaboxes.
         */
        apply_filters('owc/pdc/leges/metabox/extension-fields/add', $cmb, self::PREFIX);
    }
}
