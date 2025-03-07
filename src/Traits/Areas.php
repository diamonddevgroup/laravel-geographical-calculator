<?php

namespace DiamondDev\GeographicalCalculator\Traits;

use Illuminate\Support\Collection;

trait Areas
{
    /**
     * Get the center from the given data points.
     *
     * This method calculates the geographical center (centroid) of the provided points.
     * It stores intermediate values in the storage and returns the final result.
     *
     * @param callable|null $callback Optional callback to process the result.
     *
     * @return array|bool|Collection The calculated center as an array or a Collection if a callback is provided.
     *                               Returns false if no points are provided.
     */
    public function getCenter(callable $callback = null)
    {
        // Set the number of points in the storage.
        $this->setInStorage('pointsCount', count($this->getPoints()));

        // Check if there are any points.
        if (!$this->getFromStorage('pointsCount')) {
            return false;
        }

        // Reset all dimension values.
        $this->resetDimensions();

        // Loop through each point and add the latitude and longitude to each dimension.
        $this->through($this->getPoints(), function ($index, $point) {
            // Convert latitude and longitude to radians and store them.
            $this->setInStorage('lat', ($point[0] * pi() / 180));
            $this->setInStorage('long', ($point[1] * pi() / 180));

            // Update dimension values.
            $this->setInStorage(
                'x',
                (
                    $this->getFromStorage('x') +
                    cos($this->getFromStorage('lat')) * cos($this->getFromStorage('long'))
                )
            )->setInStorage(
                'y',
                (
                    $this->getFromStorage('y') +
                    cos($this->getFromStorage('lat')) * sin($this->getFromStorage('long'))
                )
            )->setInStorage(
                'z',
                (
                    $this->getFromStorage('z') +
                    sin($this->getFromStorage('lat'))
                )
            );
        });

        // Divide each dimension by the number of points.
        $this->resolveDimensionByPointsCount()
            // Calculate the final latitude and longitude.
            ->resolveCoordinates()
            // Store the result for future access.
            ->setResult([
                'lat' => $this->getFromStorage('lat') * 180 / pi(),
                'long' => $this->getFromStorage('long') * 180 / pi(),
            ]);

        // Return the result, optionally processed by a callback.
        return isset($callback)
            ? collect($callback($this->getResult()))
            : $this->getResult();
    }

    /**
     * Check if a point is within an area defined by a main point and a diameter.
     *
     * This method calculates the distance between the main point and the point to be checked.
     * If the distance is greater than the given diameter, the point is outside the area.
     * Otherwise, the point is within the area.
     *
     * @return bool True if the point is within the area, false otherwise.
     */
    public function isInArea()
    {
        // Store the main point and the point to calculate the area.
        $this->setInStorage('mainPointToCheck', $this->getMainPoint());
        $this->setInStorage('pointToCalculateArea', $this->getPoints()[0]);

        // Clear and reset the points.
        $this->clearPoints();
        $this->setPoints([$this->getFromStorage('mainPointToCheck'), $this->getFromStorage('pointToCalculateArea')]);

        // Calculate the distance between the main point and the point to be checked.
        $this->setInStorage(
            'distanceToCompare',
            $this->setOptions(['units' => ['km']])->getDistance(function (Collection $item) {
                return $item->first()['km'];
            })
        );

        // Return true if the distance is greater than the diameter, false otherwise.
        return $this->getFromStorage('distanceToCompare') > $this->getDiameter();
    }

    /**
     * Reset all dimension values to their initial state.
     *
     * This method sets the x, y, and z dimensions to 0.0 and stores them in the storage.
     *
     * @return Areas The current instance for method chaining.
     */
    public function resetDimensions()
    {
        $this->setInStorage('x', 0.0)
            ->setInStorage('y', 0.0)
            ->setInStorage('z', 0.0)
            ->setInStorage('dimensions', ['x', 'y', 'z']);

        return $this;
    }

    /**
     * Divide each dimension value by the number of points.
     *
     * This method loops through each dimension and divides its value by the number of points.
     *
     * @return Areas The current instance for method chaining.
     */
    private function resolveDimensionByPointsCount()
    {
        $this->through($this->getFromStorage('dimensions'), function ($index, $dimension) {
            $this->setInStorage(
                $dimension,
                ($this->getFromStorage($dimension) / $this->getFromStorage('pointsCount'))
            );
        });

        return $this;
    }

    /**
     * Calculate the final latitude and longitude values.
     *
     * This method calculates the final latitude and longitude values based on the stored dimensions.
     *
     * @return Areas The current instance for method chaining.
     */
    private function resolveCoordinates()
    {
        $this->setInStorage('long', atan2(
            $this->getFromStorage('y'),
            $this->getFromStorage('x')
        ))->setInStorage(
            'multiplied y',
            ($this->getFromStorage('y') * $this->getFromStorage('y'))
        )->setInStorage(
            'multiplied x',
            ($this->getFromStorage('x') * $this->getFromStorage('x'))
        )->setInStorage(
            'distance',
            sqrt($this->getFromStorage('multiplied x') + $this->getFromStorage('multiplied y'))
        )->setInStorage('lat', atan2($this->getFromStorage('z'), $this->getFromStorage('distance')));

        return $this;
    }
}
