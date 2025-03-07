<?php

namespace DiamondDev\GeographicalCalculator\Traits;

/**
 * Trait Debugger.
 *
 * This trait provides a method for debugging by dumping the given variable and stopping the script execution.
 */
trait Debugger
{
    /**
     * Dumps the given variable and stops the script execution.
     *
     * @param mixed $any The variable to dump.
     */
    private function debug(mixed $any)
    {
        dd($any);
    }
}
