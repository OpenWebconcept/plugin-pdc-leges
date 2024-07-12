<?php

namespace OWC\PDC\Leges\Repositories;

use OWC\PDC\Leges\RestAPI\Repositories\LegesRepository as LegesRepositoryAPI;
use WP_Query;

class LegesRepository extends LegesRepositoryAPI
{
    /**
     * Posttype definition.
     */
    protected string $posttype = 'pdc-leges';

    public function all(): array
    {
        $args = array_merge($this->queryArgs, [
            'post_type' => [$this->posttype],
            'post_status' => 'publish',
        ]);

        $this->query = new WP_Query($args);

        return $this->getQuery()->posts;
    }
}
