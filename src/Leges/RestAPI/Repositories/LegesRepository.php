<?php

namespace OWC\PDC\Leges\RestAPI\Repositories;

use OWC\PDC\Leges\RestAPI\Contracts\AbstractRepository;
use WP_Post;

class LegesRepository extends AbstractRepository
{
    /**
     * Posttype definition
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
        ];
    }
}
