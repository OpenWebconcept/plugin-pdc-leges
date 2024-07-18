<?php

namespace OWC\PDC\Leges\Traits;

trait FloatSanitizer
{
    /**
     * Sanitize and format a value to a float with two decimal places.
     */
    public function sanitizeFloat($value): string
    {
        $sanitized_value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        return number_format((float)$sanitized_value, 2, '.', '');
    }
}
