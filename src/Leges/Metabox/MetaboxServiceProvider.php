<?php

namespace OWC\PDC\Leges\Metabox;

use OWC\PDC\Base\Foundation\ServiceProvider;

class MetaboxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        add_filter('cmb2_admin_init', [new Metabox(), 'registerMetaboxes'], 10, 0);
    }
}
