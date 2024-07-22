<?php

namespace OWC\PDC\Leges\Settings;

class Settings
{
    protected array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function extensionEnabled(): bool
    {
        $isExtended = $this->settings['_owc_pdc_leges_setting_extended'] ?? '';

        return filter_var($isExtended, FILTER_VALIDATE_BOOLEAN);
    }

    public static function make(): self
    {
        $defaultSettings = [
            '_owc_pdc_leges_setting_extended' => 'off',
        ];

        return new static(wp_parse_args(get_option('_owc_pdc_leges_base_settings'), $defaultSettings));
    }
}
