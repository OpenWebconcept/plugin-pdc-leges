<?php

function deactivate(): void
{
    $timestamp = wp_next_scheduled('owc_pdc_leges_update_cron');
    wp_unschedule_event($timestamp, 'owc_pdc_leges_update_cron');
}
