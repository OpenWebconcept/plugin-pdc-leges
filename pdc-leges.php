<?php

/**
 * Plugin Name:       PDC Leges
 * Plugin URI:        https://www.openwebconcept.nl
 * Description:       PDC Leges
 * Version:           2.0.0
 * Author:            Yard Internet
 * Author URI:        https://www.yardinternet.nl/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       pdc-leges
 * Domain Path:       /languages
 */

use OWC\PDC\Leges\Autoloader;
use OWC\PDC\Leges\Foundation\Plugin;

/**
 * If this file is called directly, abort.
 */
if (! defined('WPINC')) {
    die;
}

/**
 * Manual loaded file: the autoloader.
 */
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    require_once __DIR__ . '/autoloader.php';
    $autoloader = new Autoloader();
}
/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */
add_action('plugins_loaded', function () {
    if (! class_exists('OWC\PDC\Base\Foundation\Plugin')) {
        add_action('admin_notices', function () {
            $list = '<p>' . __(
                'The following plugins are required to use the PDC Leges:',
                'pdc-leges'
            ) . '</p><ol><li>OpenPDC Base (version >= 3.0.0)</li></ol>';

            printf('<div class="notice notice-error"><p>%s</p></div>', $list);
        });

        \deactivate_plugins(\plugin_basename(__FILE__));

        return;
    }

    $plugin = (new Plugin(__DIR__))->boot();
}, 10);

include_once plugin_dir_path(__FILE__) . 'deactivate.php';
register_deactivation_hook(__FILE__, 'deactivate');
