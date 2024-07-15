<?php

namespace OWC\PDC\Leges\Includes;

class DependencyCheck
{
    public static function checkDependencies(): bool
    {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if (! is_plugin_active('pdc-base/pdc-base.php')) {
            add_action('admin_notices', [self::showPluginDependencyNotice('OpenPDC Base (version >= 3.0.0)')]);

            return false;
        }

        if (! is_plugin_active('cmb2/init.php')) {
            add_action('admin_notices', [self::showPluginDependencyNotice('CMB2')]);

            return false;
        }

        return true;
    }

    private static function showPluginDependencyNotice(string $pluginName): void
    {
        $message = __(
            __('The following plugins are required to use the PDC Leges:', 'pdc-leges'),
            'pdc-leges'
        );
        $list = sprintf('<p>%s</p><ol><li>%s</li></ol>', $message, $pluginName);

        printf('<div class="notice notice-error"><p>%s</p></div>', $list);
    }
}
