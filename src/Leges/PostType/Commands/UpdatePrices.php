<?php

namespace OWC\PDC\Leges\PostType\Commands;

class UpdatePrices
{
    public function execute(): void
    {
        $leges = $this->getLeges();

        if (empty($leges)) {
            \WP_CLI::log('No updates required.');
            return;
        }

        $this->update($leges);
    }

    protected function getLeges(): array
    {
        $query = new \WP_Query([
            'post_type'      => 'pdc-leges',
            'posts_per_page' => '-1',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => '_pdc-lege-new-price',
                    'value' => '',
                    'compare' => '!=',
                ],
                [
                    'key' => '_pdc-lege-active-date',
                    'value' => '',
                    'compare' => '!=',
                ]
            ]
        ]);

        return empty($query->posts) ? [] : $query->posts;
    }

    protected function update(array $leges): void
    {
        foreach ($leges as $lege) {
            if (! $this->shouldUpdate($lege->ID)) {
                continue;
            }

            $this->updatePostMeta($lege);
        }
    }

    public function shouldUpdate(int $postID): bool
    {
        $activeDate = \get_post_meta($postID, '_pdc-lege-active-date', true);
        
        if (! \DateTime::createFromFormat('d-m-Y', $activeDate)) {
            \WP_CLI::warning(sprintf('Could not update lege with ID [%d], date is not valid.', $postID));
            return false;
        }

        if (\DateTime::createFromFormat('d-m-Y', $activeDate) > new \DateTime()) {
            return false;
        }

        return true;
    }

    protected function updatePostMeta(\WP_Post $lege): void
    {
        $currentPrice = \get_post_meta($lege->ID, '_pdc-lege-price', true);
        $newPrice = \get_post_meta($lege->ID, '_pdc-lege-new-price', true);

        /**
         * Based on the WP_Query this value can't be empty.
         * But what if someone cleares the value after the execution of the WP_Query.
         */
        if (empty($newPrice)) {
            \WP_CLI::warning(sprintf('Could not update lege [%s], new price meta field is empty.', $lege->post_title));
            return;
        }

        $updated = \update_post_meta($lege->ID, '_pdc-lege-price', $newPrice);

        /**
         * Check if the previous and new value are the same.
         * If so updating will result in a false. This is not a reason to stop the current iteration.
         * If this is not the case something else went wrong, stop current iteration.
         */
        if (!$updated && $currentPrice !== $newPrice) {
            \WP_CLI::warning(sprintf('Could not update lege [%s].', $lege->post_title));
            return;
        }

        \WP_CLI::success(sprintf('Lege [%s] has been updated.', $lege->post_title));

        \update_post_meta($lege->ID, '_pdc-lege-new-price', '');
        \update_post_meta($lege->ID, '_pdc-lege-active-date', '');
    }
}
