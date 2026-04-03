<?php

namespace OWC\PDC\Leges\Shortcode;

use DateTime;
use Exception;
use OWC\PDC\Leges\Traits\NumberSanitizer;

class Shortcode
{
    use NumberSanitizer;

    /**
     * Default fields for leges.
     */
    protected array $defaults = [
        '_pdc-lege-active-date' => null,
        '_pdc-lege-price' => null,
        '_pdc-lege-new-price' => null,
        '_pdc-lege-percentage' => null,
        '_pdc-lege-new-percentage' => null,
        '_pdc-lege-use-percentage' => null,
    ];

    /**
     * Add the shortcode rendering.
     */
    public function addShortcode(array $attributes): string
    {
        $attributes = shortcode_atts([
            'id' => 0,
        ], $attributes);

        if (empty($attributes['id']) || (1 > $attributes['id'])) {
            return false;
        }

        if (! $this->postExists((int) $attributes['id'])) {
            return false;
        }

        ['price' => $price, 'newPrice' => $newPrice, 'dateActive' => $dateActive, 'percentage' => $percentage, 'newPercentage' => $newPercentage, 'usePercentage' => $usePercentage] = $this->extractMeta($attributes);

        if ('on' === $usePercentage) {
            if ($this->hasDate($dateActive) && $this->dateIsNow($dateActive) && '' !== $newPercentage && null !== $newPercentage) {
                $percentage = $newPercentage;
            }

            $format = apply_filters('owc/pdc/leges/shortcode/percentage/format', '<span>%s%%</span>');
            $output = sprintf($format, esc_html(str_replace('.', ',', $percentage)));
            $output = apply_filters('owc/pdc/leges/shortcode/after-format', $output);

            return wp_kses_post($output) ?? '';
        }

        if ($this->hasDate($dateActive) && $this->dateIsNow($dateActive) && (0 < strlen($newPrice) || $this->sanitizeAndCheckNumeric($newPrice))) {
            $price = $newPrice;
        }

        $format = apply_filters('owc/pdc/leges/shortcode/format', '<span>&euro; %s</span>');
        $output = sprintf($format, number_format_i18n((float) $price, 2));
        $output = apply_filters('owc/pdc/leges/shortcode/after-format', $output);

        return wp_kses_post($output) ?? '';
    }

    protected function extractMeta(array $attributes): array
    {
        $legeID = absint($attributes['id']);
        $metaData = $this->mergeWithDefaults(get_metadata('post', $legeID));

        return [
            'price' => $metaData['_pdc-lege-price'] ?? '',
            'newPrice' => $metaData['_pdc-lege-new-price'] ?? '',
            'dateActive' => $metaData['_pdc-lege-active-date'] ?? '',
            'percentage' => $metaData['_pdc-lege-percentage'] ?? '',
            'newPercentage' => $metaData['_pdc-lege-new-percentage'] ?? '',
            'usePercentage' => $metaData['_pdc-lege-use-percentage'] ?? '',
        ];
    }

    /**
     * Determines if a post, identified by the specified ID, exist
     * within the WordPress database.
     */
    protected function postExists(int $id): bool
    {
        return get_post_status($id);
    }

    /**
     * Merges the settings with defaults, to always have proper settings.
     */
    private function mergeWithDefaults(array $metaData): array
    {
        $output = [];
        foreach ($metaData as $key => $data) {
            if (! in_array($key, array_keys($this->defaults))) {
                continue;
            }

            $output[$key] = (! is_array($data)) ? $data : $data[0];
        }

        return $output;
    }

    /**
     * Readable check if date is not empty.
     */
    private function hasDate($dateActive): bool
    {
        return ! empty($dateActive);
    }

    /**
     * Return true if date from lege is smaller or equal to current date.
     */
    private function dateIsNow($dateActive): bool
    {
        try {
            $dateActive = new DateTime($dateActive);
        } catch (Exception $e) {
            return false;
        }

        return $dateActive <= new DateTime('now');
    }
}
