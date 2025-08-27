<?php

namespace DiamondDev\GeographicalCalculator\Traits;

/**
 * Trait DiametersStorage.
 *
 * This trait provides methods to store and retrieve diameters.
 * It includes methods to set and get the diameter in meters.
 *
 * @author Diamond Mubaarak
 */
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
     *
     * @return $this The current instance for method chaining.
     */
    public function setDiameter(int $diameter = 0)
    {
        $this->diameter = $diameter;

        return $this;
    }
}
