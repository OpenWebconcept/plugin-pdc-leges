<?php

/**
 * The base of the plugin.
 */

namespace OWC\PDC\Leges\Foundation;

use OWC\PDC\Base\Foundation\Plugin as BasePlugin;

/**
 * Sets the name and version of the plugin.
 */
class Plugin extends BasePlugin
{
    /**
     * Name of the plugin.
     *
     * @const string NAME
     */
    const NAME = 'pdc-leges';

    /**
     * Version of the plugin.
     * Used for setting versions of enqueue scripts and styles.
     *
     * @const string VERSION
     */
    const VERSION = '1.1.4';
}
