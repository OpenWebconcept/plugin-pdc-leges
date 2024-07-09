<?php

namespace OWC\PDC\Leges\Shortcode;

use OWC\PDC\Base\Foundation\ServiceProvider;

class ShortcodeServiceProvider extends ServiceProvider
{
    /**
     * Shortcode to be registered.
     */
    protected static string $shortcode = 'pdc::leges';

    public function register()
    {
        $shortcode = new Shortcode();
        add_shortcode(self::$shortcode, [$shortcode, 'addShortcode']);
    }

    /**
     * The generation of the shortcode.
     */
    public static function generateShortcode(int $id = 0): string
    {
        $shortcode = sprintf('[%s id="%d"]', self::$shortcode, $id);

        return sprintf('<code>%s</code>', $shortcode);
    }
}
