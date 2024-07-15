<?php

/**
 * Unschedule WP Cron Event(s)
 */
function deactivate(): void
{
    $timestamp = wp_next_scheduled('owc_pdc_leges_update_cron');

	if (! $timestamp) {
		return;
	}

    wp_unschedule_event($timestamp, 'owc_pdc_leges_update_cron');
}
