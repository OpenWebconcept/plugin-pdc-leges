<?php

namespace OWC\PDC\Leges\WPCron;

use DateTime;
use OWC\PDC\Base\Foundation\ServiceProvider;
use OWC\PDC\Leges\WPCron\Events\LegesPricesSaveFormat;
use OWC\PDC\Leges\WPCron\Events\UpdateLegesPrices;

class WPCronServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerHooks();
        $this->registerEvents();
    }

    protected function registerHooks(): void
    {
        add_action('owc_pdc_leges_update_cron', [UpdateLegesPrices::class, 'init']);
        add_action('owc_pdc_leges_prices_save_format', [LegesPricesSaveFormat::class, 'init']);
    }

    protected function registerEvents(): void
    {
        if (! wp_next_scheduled('owc_pdc_leges_update_cron')) {
            wp_schedule_event($this->timeToExecute(), 'daily', 'owc_pdc_leges_update_cron');
        }

        if (! wp_next_scheduled('owc_pdc_leges_prices_save_format') && '1' !== get_option('owc_pdc_leges_prices_save_format_updated', '')) {
            wp_schedule_single_event(time(), 'owc_pdc_leges_prices_save_format');
        }
    }

    protected function timeToExecute(): int
    {
        $currentDateTime = new DateTime('now', wp_timezone());
        $tomorrowDateTime = $currentDateTime->modify('+1 day');
        $tomorrowDateTime->setTime(6, 0, 0);

        return $tomorrowDateTime->getTimestamp();
    }
}
