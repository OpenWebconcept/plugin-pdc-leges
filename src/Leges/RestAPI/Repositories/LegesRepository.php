<?php

namespace OWC\PDC\Leges\RestAPI\Repositories;

use OWC\PDC\Leges\RestAPI\Contracts\AbstractRepository;
use OWC\PDC\Leges\Settings\Settings;
use OWC\PDC\Leges\Traits\WeekDays;
use WP_Post;

class LegesRepository extends AbstractRepository
{
    use WeekDays;

    /**
     * Posttype definition.
     */
    protected string $posttype = 'pdc-leges';

    /**
     * Prepare a post for the REST response.
     */
    public function transform(WP_Post $post): array
    {
        return array_merge([
            'id' => $post->ID,
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'date' => $post->post_date,
            'post_status' => $post->post_status,
            'price' => get_post_meta($post->ID, '_pdc-lege-price', true) ?: null,
            'new_price' => get_post_meta($post->ID, '_pdc-lege-new-price', true) ?: null,
            'new_price_active_date' => get_post_meta($post->ID, '_pdc-lege-active-date', true) ?: null,
        ], $this->extendedMetaboxValues($post));
    }

    protected function extendedMetaboxValues(WP_Post $post): array
    {
        if (! Settings::make()->extensionEnabled()) {
            return [];
        }

		/**
		 * Allows adding custom CMB2 metaboxes meta values to the output of the REST API.
		 */
        return apply_filters('owc/pdc/leges/rest-api/output/extension-fields/add', $post, [
            'start_time' => get_post_meta($post->ID, '_pdc-lege-start-time', true) ?: null,
            'end_time' => get_post_meta($post->ID, '_pdc-lege-end-time', true) ?: null,
            'person_count_threshold' => get_post_meta($post->ID, '_pdc-lege-person-count-threshold', true) ?: null,
            'exception_price' => get_post_meta($post->ID, '_pdc-lege-exception-price', true) ?: null,
            'applicable_days' => $this->formatApplicableDays($post),
        ]);
    }

    protected function formatApplicableDays(WP_Post $post): array
    {
        $days = get_post_meta($post->ID, '_pdc-lege-applicable-days', true) ?: [];

        if (empty($days) || ! is_array($days)) {
            return [];
        }

		$filteredDays = array_intersect_key($this->getWeekDays(), array_flip($days));

        return array_map(function ($numericDayKey, $day) {
            return [
                'day' => $day,
                'value' => (int) $numericDayKey,
            ];
        }, array_keys($filteredDays), $filteredDays);
    }
}
