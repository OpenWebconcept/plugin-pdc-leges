<?php

/**
 * Handles shortcode generation.
 */

namespace OWC\PDC\Leges\Shortcode;

/**
 * Handles shortcode generation.
 */
class Shortcode
{

    /**
     * Default fields for leges.
     *
     * @var array $defaults
     */
    protected $defaults = [
        '_pdc-lege-active-date' => null,
        '_pdc-lege-price' => null,
        '_pdc-lege-new-price' => null,
    ];

    /**
     * Add the shortcode rendering.
     *
     * @param $attributes
     *
     * @return string
     */
    public function addShortcode($attributes)
    {
        $attributes = shortcode_atts(['id' => 0], $attributes);

        if (!isset($attributes['id']) || empty($attributes['id']) || ($attributes['id'] < 1)) {
            return false;
        }

        if (!$this->postExists($attributes['id'])) {
            return false;
        }

        $id = absint($attributes['id']);
        $metaData = $this->mergeWithDefaults(get_metadata('post', $id));
        $price = $metaData['_pdc-lege-price'];
        $newPrice = $metaData['_pdc-lege-new-price'];
        $dateActive = $metaData['_pdc-lege-active-date'];

        if ($this->hasDate($dateActive) and $this->dateIsNow($dateActive)) {
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
     *
     * @param    int $id The ID of the post to check
     *
     * @return   bool          True if the post exists; otherwise, false.
     */
    protected function postExists($id)
    {
        return get_post_status($id);
    }

    /**
     * Merges the settings with defaults, to always have proper settings.
     *
     * @param $metaData
     *
     * @return array
     */
    private function mergeWithDefaults($metaData)
    {
        $output = [];
        foreach ($metaData as $key => $data) {
            if (! in_array($key, array_keys($this->defaults))) {
                continue;
            }

            $output[$key] = (!is_array($data)) ? $data : $data[0];
        }

        return $output;
    }

    /**
     * Readable check if date is not empty.
     *
     * @param $dateActive
     *
     * @return bool
     */
    private function hasDate($dateActive)
    {
        return !empty($dateActive);
    }

    /**
     * Return true if date from lege is smaller or equal to current date.
     *
     * @param $dateActive
     *
     * @return bool
     */
    private function dateIsNow($dateActive)
    {
        return (new \DateTime($dateActive) <= new \DateTime('now'));
    }
}
