<?php

namespace DiamondDev\GeographicalCalculator\Traits;

trait Formatter
{
    /**
     * Format the distance key with a prefix from the configuration.
     *
     * @param string $key The key to format.
     *
     * @return string The formatted distance key.
     *
     * @author Karam Mustafa
     */
    private function formatDistanceKey(string $key)
    {
        $prefix = config('geographical_calculator.distance_key_prefix');

        return $prefix . $key;
    }
}
