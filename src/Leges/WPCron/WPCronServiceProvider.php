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
        add_action('owc_pdc_leges_prices_save_format', [LegesPricesSaveFormat::class, 'init']);
        add_action('owc_pdc_leges_update_cron', [UpdateLegesPrices::class, 'init']);
    }

    protected function registerEvents(): void
    {
        if (! wp_next_scheduled('owc_pdc_leges_prices_save_format') && '1' !== get_option('owc_pdc_leges_prices_save_format_updated', '')) {
            wp_schedule_single_event($this->timeToExecute(5), 'owc_pdc_leges_prices_save_format');
        }

        if (! wp_next_scheduled('owc_pdc_leges_update_cron')) {
            wp_schedule_event($this->timeToExecute(6), 'daily', 'owc_pdc_leges_update_cron');
        }
    }

    protected function timeToExecute(int $hour, int $minut = 0, int $second = 0, int $microsecond = 0): int
    {
        $currentDateTime = new DateTime('now', wp_timezone());
        $tomorrowDateTime = $currentDateTime->modify('+1 day');
        $tomorrowDateTime->setTime($hour, $minut, $second, $microsecond);

        return $tomorrowDateTime->getTimestamp();
    }
}
