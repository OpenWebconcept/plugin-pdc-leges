<?php

namespace OWC\PDC\Leges\Shortcode;

use DateTime;
use Exception;

class Shortcode
{
    /**
     * Default fields for leges.
     */
    protected array $defaults = [
        '_pdc-lege-active-date' => null,
        '_pdc-lege-price' => null,
        '_pdc-lege-new-price' => null,
    ];

    /**
     * Add the shortcode rendering.
     */
    public function addShortcode(array $attributes): string
    {
        $attributes = shortcode_atts([
            'id' => 0,
        ], $attributes);

        if (! isset($attributes['id']) || empty($attributes['id']) || (1 > $attributes['id'])) {
            return false;
        }

        if (! $this->postExists((int) $attributes['id'])) {
            return false;
        }

        $id = absint($attributes['id']);
        $metaData = $this->mergeWithDefaults(get_metadata('post', $id));
        $price = $metaData['_pdc-lege-price'];
        $newPrice = $metaData['_pdc-lege-new-price'];
        $dateActive = $metaData['_pdc-lege-active-date'];

        if ($this->hasDate($dateActive) && $this->dateIsNow($dateActive)) {
            $price = $newPrice;
        }

        $format = apply_filters('owc/pdc/leges/shortcode/format', '<span>&euro; %s</span>');
        $output = sprintf($format, $price);
        $output = apply_filters('owc/pdc/leges/shortcode/after-format', $output);

        return $output;
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
