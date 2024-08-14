<?php

namespace OWC\PDC\Leges\RestAPI;

use OWC\PDC\Base\Foundation\ServiceProvider;
use OWC\PDC\Leges\RestAPI\Controllers\LegesController;
use WP_REST_Server;

class RestAPIServiceProvider extends ServiceProvider
{
    private string $namespace = 'owc/pdc/v1';

    public function register(): void
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes(): void
    {
        register_rest_route($this->namespace, 'leges', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [new LegesController, 'getLeges'],
            'permission_callback' => '__return_true',
            'args' => [
                'limit' => [
                    'description' => 'Number of posts per page.',
                    'type' => 'integer',
                    'default' => 10,
                    'minimum' => -1,
                    'maximum' => 100,
                ],
                'page' => [
                    'description' => 'Current page number.',
                    'type' => 'integer',
                    'default' => 1,
                ],
                'meta_key' => [
                    'description' => 'Meta key to filter by.',
                    'type' => 'string',
                    'required' => false,
                ],
                'meta_value' => [
                    'description' => 'Meta value to filter by.',
                    'type' => 'string',
                    'required' => false,
                ],
                'ids' => [
                    'description' => 'Filter on one or more IDs using a comma-separated string of IDs.',
                    'type' => 'string',
                    'default' => '',
                    'required' => false,
                ],
            ],
        ]);

        register_rest_route($this->namespace, 'leges/(?P<id>\d+)', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [new LegesController, 'getLege'],
            'permission_callback' => '__return_true',
            'args' => [
                'id' => [
                    'description' => 'ID of the post.',
                    'type' => 'integer',
                    'default' => 0,
                ],
            ],
        ]);

        register_rest_route($this->namespace, 'leges/(?P<slug>[\w-]+)', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [new LegesController, 'getLegeBySlug'],
            'permission_callback' => '__return_true',
            'args' => [
                'slug' => [
                    'description' => 'Slug of the post.',
                    'type' => 'string',
                    'default' => '',
                ],
            ],
        ]);
    }
}
