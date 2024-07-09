<?php

/**
 * The base of the plugin.
 */

namespace OWC\PDC\Leges\Foundation;

use OWC\PDC\Base\Foundation\Plugin as BasePlugin;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Sets the name and version of the plugin.
 */
class Plugin extends BasePlugin
{
    /**
     * Name of the plugin.
     */
    const NAME = 'pdc-leges';

    /**
     * Version of the plugin.
     * Used for setting versions of enqueue scripts and styles.
     */
    const VERSION = '1.2.5';

    protected function checkForUpdate()
    {
        if (! class_exists(PucFactory::class) || $this->isExtendedClass()) {
            return;
        }

        try {
            $updater = PucFactory::buildUpdateChecker(
                'https://github.com/OpenWebconcept/plugin-pdc-leges/',
                $this->rootPath . '/pdc-leges.php',
                self::NAME
            );

            $updater->getVcsApi()->enableReleaseAssets();
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }
    }
}
