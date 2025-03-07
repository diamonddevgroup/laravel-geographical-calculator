<?php

namespace DiamondDev\GeographicalCalculator\Traits;

trait Looper
{
    /**
     * Iterate through each item in the provided data and apply the given callback function.
     *
     * @param array $data        The data to iterate over.
     * @param callable $callback The callback function to apply to each item. The callback receives two parameters: the index and the item.
     *
     * @return void
     *
     * @author Karam Mustafa
     */
    public function through(array $data, callable $callback)
    {
        foreach ($data as $index => $item) {
            $callback($index, $item);
        }
    }
}
