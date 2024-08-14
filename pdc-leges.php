<?php

/**
 * Plugin Name:       PDC Leges
 * Plugin URI:        https://www.openwebconcept.nl
 * Description:       PDC Leges
 * Version:           2.2.0
 * Author:            Yard | Digital agency
 * Author URI:        https://www.yard.nl/
 * License:           EUPL-1.2
 * License URI:       https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12
 * Text Domain:       pdc-leges
 * Domain Path:       /languages
 */

use OWC\PDC\Leges\Autoloader;
use OWC\PDC\Leges\Foundation\Plugin;
use OWC\PDC\Leges\Includes\DependencyCheck;

/**
 * If this file is called directly, abort.
 */
if (! defined('WPINC')) {
    die;
}

require_once plugin_dir_path(__FILE__) . 'includes/dependency-check.php';
require_once plugin_dir_path(__FILE__) . 'includes/deactivate.php'; // Function is used in register_deactivation_hook.

/**
 * Autoload files using Composer autoload or fallback to custom autoloader.
 */
if (file_exists(plugin_dir_path(__FILE__) . 'vendor/autoload.php')) {
    require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
} else {
    require_once plugin_dir_path(__FILE__) . 'autoloader.php';
	if (class_exists('OWC\PDC\Leges\Autoloader')) {
    	$autoloader = new Autoloader();
	}
}

/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */
add_action('plugins_loaded', function () {
    $plugin = (new Plugin(__DIR__))->boot();

	// The plugin must be activated before the translations can be loaded.
	if (! DependencyCheck::checkDependencies()) {
		deactivate_plugins(plugin_basename(__FILE__));

        return;
    }
}, 10);

/**
 * Deactivation.
 */
register_deactivation_hook(__FILE__, 'deactivate');
