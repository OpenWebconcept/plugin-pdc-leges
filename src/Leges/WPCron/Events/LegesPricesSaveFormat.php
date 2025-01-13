<?php

namespace OWC\PDC\Leges\WPCron\Events;

use OWC\PDC\Leges\Repositories\LegesRepository;
use OWC\PDC\Leges\Traits\NumberSanitizer;
use OWC\PDC\Leges\WPCron\Contracts\AbstractEvent;
use WP_Post;

class LegesPricesSaveFormat extends AbstractEvent
{
    use NumberSanitizer;

    private const META_KEY_OLD_PRICE = '_pdc-lege-price';
    private const META_KEY_NEW_PRICE = '_pdc-lege-new-price';

    protected function execute(): void
    {
        $legesPosts = $this->getLeges();

        if (empty($legesPosts)) {
            $this->logError('No leges found to update the existing leges prices as float strings.');

            return;
        }

        $this->processLegesPrices($legesPosts);
        update_option('owc_pdc_leges_prices_save_format_updated', '1');
    }

    /**
     * Retrieve leges that need their prices saved as float strings.
     *
     * @return WP_Post[]
     */
    protected function getLeges(): array
    {
        $repository = new LegesRepository();
        $repository->addQueryArguments([
            'posts_per_page' => -1,
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => self::META_KEY_OLD_PRICE,
                    'value' => '',
                    'compare' => '!=',
                ],
                [
                    'key' => self::META_KEY_NEW_PRICE,
                    'value' => '',
                    'compare' => '!=',
                ],
            ],
        ]);

        return $repository->all();
    }

    /**
     * Update the prices for the given leges posts.
     *
     * @param WP_Post[] $legesPosts
     */
    protected function processLegesPrices(array $legesPosts): void
    {
        foreach ($legesPosts as $legesPost) {
            $this->sanitizeAndUpdatePriceMeta($legesPost);
        }
    }

    /**
     * Sanitize and update the price meta values to ensure they are stored as float strings.
     *
     * This method retrieves the current and future price meta values from an older version of the plugin,
     * where prices were saved as strings with commas (e.g., "1,79"). It sanitizes these values to ensure
     * they are in the correct float format and updates the post meta accordingly.
     *
     * @param WP_Post $legesPost
     */
    protected function sanitizeAndUpdatePriceMeta(WP_Post $legesPost): void
    {
        $oldPrice = get_post_meta($legesPost->ID, self::META_KEY_OLD_PRICE, true);
        $newPrice = get_post_meta($legesPost->ID, self::META_KEY_NEW_PRICE, true);

        if (0 < strlen($oldPrice) || $this->sanitizeAndCheckNumeric($oldPrice)) {
            update_post_meta($legesPost->ID, self::META_KEY_OLD_PRICE, $this->convertToFloatString($oldPrice));
        }

        if (0 < strlen($newPrice) || $this->sanitizeAndCheckNumeric($newPrice)) {
            update_post_meta($legesPost->ID, self::META_KEY_NEW_PRICE, $this->convertToFloatString($newPrice));
        }
    }

    protected function convertToFloatString(string $price): string
    {
        $price = $this->prepareFloatSanitation($price);

        return $this->sanitizeFloat($price);
    }

    /**
     * Prepares a price string by removing non-standard characters and normalizing its format.
     */
    protected function prepareFloatSanitation(string $price): string
    {
        // Remove non-numeric characters except commas and dots.
        $price = preg_replace('/[^\d,\.]/', '', $price);

        // If both comma and dot exist, decide which is the decimal separator.
        if (strpos($price, ',') !== false && strpos($price, '.') !== false) {
            // If comma is before dot, treat comma as thousand separator.
            if (strpos($price, ',') < strpos($price, '.')) {
                $price = str_replace(',', '', $price); // Remove commas.
            } else {
                // If dot is before comma, treat dot as thousand separator.
                $price = str_replace('.', '', $price); // Remove dots.
                $price = str_replace(',', '.', $price); // Replace last comma with dot.
            }
        } elseif (strpos($price, ',') !== false) {
            // If only a comma is present, replace it with a dot (European notation).
            $price = str_replace(',', '.', $price);
        }

        // Remove thousand separators if any remain (just in case).
        $price = str_replace(',', '', $price);
        $price = str_replace('.', '', substr($price, 0, -3)) . substr($price, -3);

        return $price;
    }
}
