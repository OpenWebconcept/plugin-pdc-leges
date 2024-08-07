<?php

namespace OWC\PDC\Leges\WPCron;

use DateTime;
use OWC\PDC\Base\Foundation\ServiceProvider;
use OWC\PDC\Leges\WPCron\Events\UpdateLegesPrices;

class WPCronServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        add_action('owc_pdc_leges_update_cron', [UpdateLegesPrices::class, 'init']);

        if (! wp_next_scheduled('owc_pdc_leges_update_cron')) {
            wp_schedule_event($this->timeToExecute(), 'daily', 'owc_pdc_leges_update_cron');
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
