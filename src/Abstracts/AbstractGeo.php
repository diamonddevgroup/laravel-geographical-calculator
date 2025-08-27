<?php

namespace DiamondDev\GeographicalCalculator\Abstracts;

/**
 * Abstract class AbstractGeo.
 *
 * This class provides a base implementation for geographical calculations.
 * It includes a method to conditionally execute a callback function.
 *
 * @author  Karam Mustafa
 */
abstract class AbstractGeo
{
    /**
     * Conditionally execute a callback function.
     *
     * This method checks if a condition is set, and if so, executes the provided callback function.
     * If the condition or callback is not set, it returns the current instance.
     *
     * @param mixed $condition The condition to check.
     * @param callable|null $callback The callback function to execute if the condition is met.
     *
     * @return AbstractGeo The current instance of the class.
     */
    public function checkIf(mixed $condition, callable $callback = null)
    {
        if (isset($condition) && isset($callback)) {
            return $callback();
        }

        return $this;
    }
}
