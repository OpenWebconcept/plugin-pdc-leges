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

        $ids = $this->parseIDs($attributes['id'] ?? 0);

        if ([] === $ids) {
            return false;
        }

        return 1 === count($ids) ? $this->renderPrice($ids[0]) : $this->renderTotal($ids);
    }

    /**
     * Split the id attribute into a list of usable lege IDs.
     *
     * Accepts a single id ("12") as well as a comma separated list ("12, 34").
     * Casts instead of using absint() so this guard runs before any WordPress call.
     *
     * @since 2.4.0
     */
    protected function parseIDs($id): array
    {
        $ids = array_map('intval', explode(',', (string) $id));
        $ids = array_filter($ids, function (int $id): bool {
            return 0 < $id;
        });

        return array_values(array_unique($ids));
    }

    /**
     * Render the price - or the percentage - of a single lege.
     */
    protected function renderPrice(int $id): string
    {
        if (! $this->postExists($id)) {
            return false;
        }

        ['price' => $price, 'newPrice' => $newPrice, 'dateActive' => $dateActive, 'percentage' => $percentage, 'newPercentage' => $newPercentage, 'usePercentage' => $usePercentage] = $this->extractMeta($id);

        if ('on' === $usePercentage) {
            if ($this->hasDate($dateActive) && $this->dateIsNow($dateActive) && '' !== $newPercentage && null !== $newPercentage) {
                $percentage = $newPercentage;
            }

            $format = apply_filters('owc/pdc/leges/shortcode/percentage/format', '<span>%s%%</span>');
            $output = sprintf($format, esc_html(str_replace('.', ',', $percentage)));
            $output = apply_filters('owc/pdc/leges/shortcode/after-format', $output);

            return wp_kses_post($output) ?? '';
        }

        return $this->formatPrice((float) $this->resolvePrice($price, $newPrice, $dateActive));
    }

    /**
     * Render the sum of the prices of multiple leges.
     *
     * Leges which do not exist and leges which express a percentage instead of a
     * price are skipped: a percentage cannot be added to an amount. When every
     * given id is skipped there is nothing to show.
     *
     * @since 2.4.0
     */
    protected function renderTotal(array $ids): string
    {
        $total = 0.0;
        $hasPrice = false;

        foreach ($ids as $id) {
            if (! $this->postExists($id)) {
                continue;
            }

            ['price' => $price, 'newPrice' => $newPrice, 'dateActive' => $dateActive, 'usePercentage' => $usePercentage] = $this->extractMeta($id);

            if ('on' === $usePercentage) {
                continue;
            }

            $hasPrice = true;

            // Prices saved through Quick Edit are not sanitized and may hold a comma decimal.
            $total += (float) $this->sanitizeFloat((string) $this->resolvePrice($price, $newPrice, $dateActive));
        }

        return $hasPrice ? $this->formatPrice($total) : false;
    }

    /**
     * Return the price which is active for a lege right now.
     *
     * The new price takes over once its active date has passed.
     *
     * @since 2.4.0
     *
     * @return mixed
     */
    protected function resolvePrice($price, $newPrice, $dateActive)
    {
        if ($this->hasDate($dateActive) && $this->dateIsNow($dateActive) && (0 < strlen((string) $newPrice) || $this->sanitizeAndCheckNumeric((string) $newPrice))) {
            return $newPrice;
        }

        return $price;
    }

    /**
     * Apply the shortcode output format to an amount.
     *
     * @since 2.4.0
     */
    protected function formatPrice(float $price): string
    {
        $format = apply_filters('owc/pdc/leges/shortcode/format', '<span>&euro; %s</span>');
        $output = sprintf($format, number_format_i18n($price, 2));
        $output = apply_filters('owc/pdc/leges/shortcode/after-format', $output);

        return wp_kses_post($output) ?? '';
    }

    protected function extractMeta(int $id): array
    {
        $legeID = absint($id);
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
