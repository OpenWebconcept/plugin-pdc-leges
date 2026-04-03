<?php

namespace OWC\PDC\Leges\Metabox;

use OWC\PDC\Base\Foundation\ServiceProvider;
use OWC\PDC\Leges\Foundation\Plugin;

class MetaboxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        add_filter('cmb2_admin_init', [new Metabox(), 'registerMetaboxes'], 10, 0);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts(): void
    {
        $screen = get_current_screen();

        if (! $screen || 'pdc-leges' !== $screen->post_type || 'post' !== $screen->base) {
            return;
        }

        wp_enqueue_script(
            'pdc-leges-metabox-toggle',
            plugins_url('/js/metabox-toggle.js', $this->plugin->getRootPath() . '/pdc-leges.php'),
            [],
            Plugin::VERSION,
            true
        );
    }
}
