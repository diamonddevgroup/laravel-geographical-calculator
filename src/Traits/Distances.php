<?php

namespace DiamondDev\GeographicalCalculator\Traits;

use Exception;
use Illuminate\Support\Collection;

/**
 * Trait Distances.
 *
 * This trait provides methods to calculate distances between points.
 * It includes methods to set and get the units, calculate the distance between points,
 *
 * @author Diamond Mubaarak
 */
trait Distances
{
    /**
     * Available units for distance calculation.
     *
     * @var array
     */
    private array $units = [
        'mile' => 1,
        'km' => 1.609344,
        'm' => (1.609344 * 1000),
        'cm' => (1.609344 * 100),
        'mm' => (1.609344 * 1000 * 1000),
    ];

    /**
     * Get the available units for distance calculation.
     *
     * @return array
     *
     * @author Karam Mustafa
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Set the available units for distance calculation.
     *
     * @param array $units The units to set.
     *
     * @return $this
     */
    public function setUnits(array $units)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * Constructor to initialize the instance.
     *
     * @author Karam Mustafa
     */
    public function __construct()
    {
        $this->resolveUnits();
    }

    /**
     * Calculate the distance between multiple points.
     *
     * @param callable|null $callback Optional callback to process the result.
     *
     * @return array|Collection
     *
     * @throws Exception
     *
     * @author Karam Mustafa
     */
    public function getDistance(callable $callback = null)
    {
        $this->through($this->getPoints(), function ($index, $point) {
            // Check if we have not reached the last point yet.
            if (isset($this->getPoints()[$index + 1])) {
                // Initialize and calculate sine and cosine values.
                $this->setSin($this->getAngle($point[0], $this->getPoints($index + 1)[0]))
                    ->setCos($this->getAngle($point[0], $this->getPoints($index + 1)[0], 'cos'))
                    // Set the position of this loop in the local storage.
                    ->setInStorage('position', ($index + 1) . '-' . ($index + 2))
                    // Set the first longitude.
                    ->setLongitude($point[1])
                    // Set the second longitude.
                    ->setLongitude($this->getPoints($index + 1)[1])
                    // Set the formatted key that binds with the prefix config.
                    ->setInStorage(
                        'distance_key',
                        $this->formatDistanceKey($this->getFromStorage('position'))
                    )
                    // Save the results.
                    ->setResult([$this->getFromStorage('distance_key') => $this->calcDistance()]);
            }
        });

        return $this->resolveCallbackResult($this->cleanDistanceResult(), $callback);
    }

    /**
     * Get the sine or cosine of the angle between two latitudes.
     *
     * @param int|float $firstLat The first latitude.
     * @param int|float $secondLat The second latitude.
     * @param string $angle The type of angle calculation ('sin' or 'cos').
     *
     * @return float
     *
     * @author Karam Mustafa
     */
    private function getAngle(int|float $firstLat, int|float $secondLat, string $angle = 'sin')
    {
        // Convert the first value to radian and get result sin or cos method,
        // convert the second value to radian and get result sin or cos method
        return $angle(deg2rad($firstLat)) * $angle(deg2rad($secondLat));
    }

    /**
     * Get the cosine of the angle between two longitudes.
     *
     * @return float
     *
     * @author Karam Mustafa
     */
    private function getValueForAngleBetween()
    {
        return cos(deg2rad($this->getLongs()[0] - $this->getLongs()[1]));
    }

    /**
     * Perform the distance calculation process.
     *
     * @return array
     *
     * @throws Exception
     *
     * @author Karam Mustafa
     */
    private function calcDistance()
    {
        $this->setInStorage(
            'distance',
            acos($this->getSin() + $this->getCos() * $this->getValueForAngleBetween())
        )->setInStorage(
            'rad2deg',
            rad2deg($this->getFromStorage('distance'))
        )->setInStorage(
            'correctDistanceValue',
            $this->correctDistanceValue($this->getFromStorage('rad2deg'))
        );

        return $this->resolveDistanceWithUnits($this->getFromStorage('correctDistanceValue'));
    }

    /**
     * Correct the calculated distance value.
     *
     * @param float $distance The distance to correct.
     *
     * @author Karam Mustafa
     */
    private function correctDistanceValue(float $distance)
    {
        return $distance * 60 * 1.1515;
    }

    /**
     * Resolve the distance with the selected units.
     *
     * @param float $distance The distance to resolve.
     *
     * @return array
     *
     * @throws Exception
     *
     * @author Karam Mustafa
     */
    private function resolveDistanceWithUnits(float $distance)
    {
        if (isset($this->getOptions()['units']) && !empty($this->getOptions('units'))) {
            // Loop through each unit and calculate the distance.
            $this->through($this->getOptions()['units'], function ($index, $unit) use ($distance) {
                $this->checkIfUnitExists($unit)
                    // Store the calculated distance for the unit.
                    ->setInStorage($unit, $distance * $this->getUnits()[$unit]);
            });
        } else {
            // If no units are specified, use the default unit (mile).
            $this->setInStorage('mile', $distance * $this->getUnits()['mile']);
        }

        // Remove unnecessary results from storage and return the final results.
        $this->removeFromStorage('position', 'distance_key', 'distance', 'rad2deg', 'correctDistanceValue');

        return $this->getFromStorage();
    }

    /**
     * Resolve the units from the configuration.
     *
     * @author Karam Mustafa
     */
    private function resolveUnits()
    {
        if (config('geographical_calculator.units')) {
            $this->setUnits(config('geographical_calculator.units'));
        }
    }

    /**
     * Check if the given unit is available in the unit property or configuration.
     *
     * @param string $unit The unit to check.
     *
     * @return $this
     *
     * @throws Exception
     *
     * @author Karam Mustafa
     */
    private function checkIfUnitExists(string $unit)
    {
        if (!isset($this->getUnits()[$unit])) {
            throw new Exception("the unit ['$unit'] dose not available in units config");
        }

        return $this;
    }

    /**
     * Calculate the distance of each point to the main point.
     *
     * @throws Exception
     *
     * @author Karam Mustafa
     */
    private function resolveEachDistanceToMainPoint()
    {
        // Store all points in the 'points' key in the storage.
        $this->setInStorage('points', $this->getPoints());

        // Clear all points after storing them in the storage.
        $this->clearPoints();

        // Get the first unit from the configuration or use the default unit.
        // The specific unit used for sorting the result does not matter.
        $this->setInStorage('unit', collect($this->getUnits())->keys()->first());

        $this->through($this->getFromStorage('points'), function ($index, $point) {

            // Calculate the distance for each point in the 'points' array
            // and append this distance to the 'distancesEachPointToMainPoint' storage key.
            $this->appendToStorage(
                'distancesEachPointToMainPoint',
                $this->setPoints([$this->getMainPoint(), $point])
                    ->getDistance(function (Collection $result) {
                        return $result->first()[$this->getFromStorage('unit')];
                    })
            );

            // Clear the points to calculate a new distance.
            $this->clearPoints();
        });
    }

    /**
     * Get only the results related to the units.
     *
     * @return mixed
     *
     * @author Karam Mustafa
     */
    public function cleanDistanceResult()
    {
        return $this->getResult(function ($result) {
            return collect($result)->filter(function ($results) {
                return collect($results)->filter(function ($value, $key) {
                    return in_array($key, array_keys($this->getUnits()));
                });
            });
        })->toArray();
    }
}
