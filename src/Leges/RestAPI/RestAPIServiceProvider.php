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
        ]);

        register_rest_route($this->namespace, 'leges/(?P<id>\d+)', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [new LegesController, 'getLege'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, 'leges/(?P<slug>[\w-]+)', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [new LegesController, 'getLegeBySlug'],
            'permission_callback' => '__return_true',
        ]);
    }
}
