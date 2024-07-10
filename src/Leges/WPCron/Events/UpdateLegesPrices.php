<?php

namespace OWC\PDC\Leges\WPCron\Events;

use DateTime;
use OWC\PDC\Leges\WPCron\Contracts\AbstractEvent;
use WP_Post;
use WP_Query;

class UpdateLegesPrices extends AbstractEvent
{
    private const META_NEW_PRICE = '_pdc-lege-new-price';
    private const META_ACTIVE_DATE = '_pdc-lege-active-date';
    private const META_PRICE = '_pdc-lege-price';

    /**
     * Execute the cron job to update leges prices.
     */
    protected function execute(): void
    {
        $leges = $this->getLeges();

        if (empty($leges)) {
            $this->logError('no leges prices updates required.');

            return;
        }

        $this->update($leges);
    }

    /**
     * Retrieve leges that need updating.
     *
     * @return WP_Post[]
     */
    protected function getLeges(): array
    {
        $query = new WP_Query([
            'post_type' => 'pdc-leges',
            'posts_per_page' => -1,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => self::META_NEW_PRICE,
                    'value' => '',
                    'compare' => '!=',
                ],
                [
                    'key' => self::META_ACTIVE_DATE,
                    'value' => '',
                    'compare' => '!=',
                ],
            ],
        ]);

        return $query->posts ?: [];
    }

    /**
     * Update the leges prices.
     *
     * @param WP_Post[] $leges
     */
    protected function update(array $leges): void
    {
        foreach ($leges as $lege) {
            if (! $this->shouldUpdate($lege->ID)) {
                continue;
            }

            $this->updatePostMeta($lege);
        }
    }

    /**
     * Determine if a lege should be updated based on the active date.
     */
    public function shouldUpdate(int $postID): bool
    {
        $activeDate = get_post_meta($postID, self::META_ACTIVE_DATE, true);

        if (empty($activeDate) || ! $this->isValidDate($activeDate)) {
            $this->logError(sprintf('could not update lege with ID [%d], date is not valid.', $postID));

            return false;
        }

        if ($this->isFutureDate($activeDate)) {
            return false;
        }

        return true;
    }

    /**
     * Update the post meta for a lege.
     */
    protected function updatePostMeta(WP_Post $lege): void
    {
        $currentPrice = get_post_meta($lege->ID, self::META_PRICE, true);
        $newPrice = get_post_meta($lege->ID, self::META_NEW_PRICE, true);

        if (empty($newPrice)) {
            $this->logError(sprintf('could not update lege [%s], new price meta field is empty.', $lege->post_title));

            return;
        }

        $updated = update_post_meta($lege->ID, self::META_PRICE, $newPrice);

        if (! $updated && $currentPrice !== $newPrice) {
            $this->logError(sprintf('could not update lege [%s].', $lege->post_title));

            return;
        }

        update_post_meta($lege->ID, self::META_NEW_PRICE, '');
        update_post_meta($lege->ID, self::META_ACTIVE_DATE, '');
    }

    /**
     * Validate if the given date is in the correct format.
     */
    private function isValidDate(string $date): bool
    {
        return DateTime::createFromFormat('d-m-Y', $date) !== false;
    }

    /**
     * Check if the given date is in the future.
     */
    private function isFutureDate(string $date): bool
    {
        $dateTime = DateTime::createFromFormat('d-m-Y', $date);

        return $dateTime > new DateTime();
    }
}
