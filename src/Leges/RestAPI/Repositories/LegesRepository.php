<?php

namespace OWC\PDC\Leges\RestAPI\Repositories;

use OWC\PDC\Leges\RestAPI\Contracts\AbstractRepository;
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
        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'date' => $post->post_date,
            'post_status' => $post->post_status,
            'price' => get_post_meta($post->ID, '_pdc-lege-price', true) ?: null,
            'new_price' => get_post_meta($post->ID, '_pdc-lege-new-price', true) ?: null,
            'new_price_active_date' => get_post_meta($post->ID, '_pdc-lege-active-date', true) ?: null,
            'start_time' => get_post_meta($post->ID, '_pdc-lege-start-time', true) ?: null,
            'end_time' => get_post_meta($post->ID, '_pdc-lege-end-time', true) ?: null,
            'person_count_treshold' => get_post_meta($post->ID, '_pdc-lege-person-count-threshold', true) ?: null,
            'exception_price' => get_post_meta($post->ID, '_pdc-lege-exception-price', true) ?: null,
            'applicable-days' => $this->handleApplicableDays($post),
        ];
    }

    protected function handleApplicableDays(WP_Post $post): array
    {
        $days = get_post_meta($post->ID, '_pdc-lege-applicable-days', true) ?: [];

        if (empty($days) || ! is_array($days)) {
            return [];
        }

        $filteredDays = array_filter($this->getWeekDays(), function ($numericDayKey) use ($days) {
            return in_array($numericDayKey, $days);
        }, ARRAY_FILTER_USE_KEY);

        return array_map(function ($numericDayKey, $day) {
            return [
                'day' => $day,
                'value' => (int) $numericDayKey,
            ];
        }, array_keys($filteredDays), $filteredDays);
    }
}
