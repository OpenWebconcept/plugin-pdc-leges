<?php

namespace OWC\PDC\Leges\WPCron;

use DateTime;
use DateTimeZone;
use OWC\PDC\Base\Foundation\ServiceProvider;
use OWC\PDC\Leges\WPCron\Events\UpdateLeges;

class WPCronServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        add_action('owc_pdc_leges_update_cron', [UpdateLeges::class, 'init']);

        if (! wp_next_scheduled('owc_pdc_leges_update_cron')) {
            wp_schedule_event($this->timeToExecute(), 'daily', 'owc_pdc_leges_update_cron');
        }
    }

    protected function timeToExecute(): int
    {
        $currentDateTime = new DateTime('now', new DateTimeZone(wp_timezone_string()));
        $tomorrowDateTime = $currentDateTime->modify('+1 day');
        $tomorrowDateTime->setTime(6, 0, 0);

        return $tomorrowDateTime->getTimestamp();
    }
}
