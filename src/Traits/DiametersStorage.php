<?php

namespace DiamondDev\GeographicalCalculator\Traits;

trait DiametersStorage
{
    /**
     * Diameter in meters.
     *
     * @var int
     */
    public int $diameter = 0;

    /**
     * Get the diameter in meters.
     *
     * @return int The diameter in meters.
     */
    public function getDiameter()
    {
        return $this->diameter;
    }

    /**
     * /**
     * Set the diameter in meters.
     *
     * @param int $diameter The diameter in meters.
     * @return DiametersStorage The current instance for method chaining.
     */
    public function setDiameter(int $diameter = 0)
    {
        $this->diameter = $diameter;

        return $this;
    }
}
