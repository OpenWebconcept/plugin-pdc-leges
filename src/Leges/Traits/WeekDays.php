<?php

namespace OWC\PDC\Leges\Traits;

trait WeekDays
{
    /**
     * Retrieve an array of week days with their numeric values as keys.
     */
    public function getWeekDays(): array
    {
        return [
            1 => __('Monday', 'pdc-leges'),
            2 => __('Tuesday', 'pdc-leges'),
            3 => __('Wednesday', 'pdc-leges'),
            4 => __('Thursday', 'pdc-leges'),
            5 => __('Friday', 'pdc-leges'),
            6 => __('Saturday', 'pdc-leges'),
            7 => __('Sunday', 'pdc-leges'),
        ];
    }
}
