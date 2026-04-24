<?php

namespace OWC\PDC\Leges\Traits;

trait NumberSanitizer
{
    /**
     * Sanitize and format a value to a float with two decimal places.
     */
    public function sanitizeFloat($value): string
    {
        return $this->sanitizeFloatWithDecimals($value, 2);
    }

    /**
     * Sanitize and format a value to a float with four decimal places.
     *
     * @since 2.3.0
     */
    public function sanitizeFloatFourDecimals($value): string
    {
        return $this->sanitizeFloatWithDecimals($value, 4);
    }

    /**
     * Sanitize and format a value to a float with the given number of decimal places.
     *
     * @since 2.3.0
     */
    private function sanitizeFloatWithDecimals($value, int $decimals): string
    {
        // Ensure the input is a string and not empty.
        if (! is_string($value) || strlen($value) < 1) {
            return '';
        }

        // Replace commas with dots (for decimal values).
        $value = str_replace(',', '.', $value);

        // Remove thousand separators (dots that are not decimal points).
        $value = preg_replace('/(?<=\d)\.(?=\d{3}(\.|$))/', '', $value);

        // Sanitize the value to allow only numeric data with fractional parts.
        $sanitized_value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        return number_format((float)$sanitized_value, $decimals, '.', '');
    }

    /**
     * Sanitizes a numeric string and checks if it represents a valid number.
     *
     * This method:
     * - Removes commas (`,`) and dots (`.`) from the input string.
     * - Validates if the resulting string is numeric.
     * - Handles edge cases where:
     *   - `0.00` or similar values might be treated as "empty" in other contexts.
     *   - `0,00` is not considered numeric by default but is sanitized and validated correctly.
     */
    public function sanitizeAndCheckNumeric(string $value): bool
    {
        $value = str_replace([',', '.'], '', $value);

        return is_numeric($value);
    }
}
