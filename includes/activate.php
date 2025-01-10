<?php

/**
 * Set the save format of all the existing prices of leges correctly on plug-in activation.
 * This is only required when updating from version ^1.0.0 to the current version.
 */
if (! function_exists('owc_pdc_leges_activate')) {
    function owc_pdc_leges_activate()
    {
        \OWC\PDC\Leges\WPCron\Events\LegesPricesSaveFormat::init();
    }
}
