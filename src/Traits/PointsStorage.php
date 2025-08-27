<?php

namespace DiamondDev\GeographicalCalculator\Traits;

/**
 * Trait PointsStorage.
 *
 * This trait provides methods to store and retrieve points.
 * It includes methods to set, get, update, and clear points.
 *
 * @author Diamond Mubaarak
 */
trait PointsStorage
{
    /**
     * All the points to handle the selected requirement.
     *
     * @var array
     */
    public array $points = [];

    /**
     * Main point used to compare the closest points to a specific point.
     *
     * @var array
     */
    public array $mainPoint = [];

    /**
     * Get all points or a specific point by index.
     *
     * @param int|null $index The index of the point to retrieve. If null, all points are returned.
     *
     * @return array The 'points' array or a specific point.
     */
    public function getPoints(int $index = null)
    {
        return $this->points[$index] ?? $this->points;
    }

    /**
     * Add a new point to the 'points' array.
     *
     * @param array $point The point to add.
     *
     * @return $this The current instance for method chaining.
     */
    public function setPoint(array $point)
    {
        $this->points[] = $point;

        return $this;
    }

    /**
     * Update a specific point by index using a callback function.
     *
     * @param int $indexAt The index of the point to update.
     * @param callable|null $callback The callback function to apply to the point.
     *
     * @return $this The current instance for method chaining.
     */
    public function updatePoint(int $indexAt = 0, callable $callback = null)
    {
        $this->points[$indexAt] = $callback($this->points[$indexAt]);

        return $this;
    }

    /**
     * Get the main point.
     *
     * @return array The main point.
     */
    public function getMainPoint()
    {
        return $this->mainPoint;
    }

    /**
     * Set the main point.
     *
     * @param array $point The main point to set.
     *
     * @return $this The current instance for method chaining.
     */
    public function setMainPoint(array $point)
    {
        $this->mainPoint = $point;

        return $this;
    }

    /**
     * Set multiple points at once.
     *
     * @param array $points The points to set.
     *
     * @return $this The current instance for method chaining.
     */
    public function setPoints(array $points)
    {
        $this->points = array_merge($this->points, $points);

        return $this;
    }

    /**
     * Replace all existing points with a new set of points.
     *
     * @param array $points The new points to set.
     *
     * @return $this The current instance for method chaining.
     */
    public function replacePoints(array $points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Clear all stored points.
     *
     * @return $this The current instance for method chaining.
     */
    public function clearPoints()
    {
        $this->points = [];

        return $this;
    }
}
