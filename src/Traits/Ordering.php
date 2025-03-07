<?php

namespace DiamondDev\GeographicalCalculator\Traits;

use Exception;

trait Ordering
{
    /**
     * An array to keep track of points that have been appended before.
     *
     * @var array
     */
    private array $pointsAppendedBefore = [];

    /**
     * Get the closest point to the main point.
     *
     * This method calculates the distance from the main point to each point,
     * determines the closest point, and optionally applies a callback function
     * to the result.
     *
     * @param callable|null $callback An optional callback function to apply to the result.
     *
     * @return mixed The closest point to the main point, optionally processed by the callback.
     *
     * @throws Exception If there is an error during the distance calculation process.
     */
    public function getClosest(callable $callback = null)
    {
        $this->resolveEachDistanceToMainPoint();

        // Set the closest point index after sorting the distances result.
        $this->setInStorage(
            'closestPointIndex',
            collect($this->getFromStorage('distancesEachPointToMainPoint'))->sort()->keys()->first()
        );

        $this->setResult([
            'closest' => [
                $this->getFromStorage('closestPointIndex') => $this->getFromStorage('points')[$this->getFromStorage('closestPointIndex')],
            ],
        ]);

        return $this->resolveCallbackResult($this->getResultByKey('closest'), $callback);
    }

    /**
     * Get the farthest point from the main point.
     *
     * This method calculates the distance from the main point to each point,
     * determines the farthest point, and optionally applies a callback function
     * to the result.
     *
     * @param callable|null $callback An optional callback function to apply to the result.
     *
     * @return mixed The farthest point from the main point, optionally processed by the callback.
     *
     * @throws Exception If there is an error during the distance calculation process.
     */
    public function getFarthest(callable $callback = null)
    {
        $this->resolveEachDistanceToMainPoint();

        // Set the farthest point index after sorting the distance result in descending order.
        $this->setInStorage(
            'farthestPointIndex',
            collect($this->getFromStorage('distancesEachPointToMainPoint'))->sortDesc()->keys()->first()
        );

        $this->setResult([
            'farthest' => [
                $this->getFromStorage('farthestPointIndex') => $this->getFromStorage('points')[$this->getFromStorage('farthestPointIndex')],
            ],
        ]);

        return $this->resolveCallbackResult($this->getResultByKey('farthest'), $callback);
    }

    /**
     * Add a key to each point and use the Nearest Neighbor Algorithm to determine the order of the points.
     *
     * @return mixed The ordered points based on the Nearest Neighbor Algorithm.
     *
     * @throws Exception If there is an error during the distance calculation process.
     */
    public function getOrderByNearestNeighbor()
    {
        // Add a key to each point.
        $this->resolveKeyForEachPoint();

        // Append the main point as the first point in the points array.
        $this->replacePoints(array_merge([
            [$this->getMainPoint()[0], $this->getMainPoint()[1], 'key' => 0],
        ], $this->getPoints()));

        return $this->nearestNeighborAlgorithm($this->getPoints());
    }

    /**
     * Get the closest point to the main point using the Nearest Neighbor Algorithm.
     *
     * This method iterates through the provided points to find the closest point to the main point.
     * It uses the Nearest Neighbor Algorithm to determine the order of the points.
     *
     * @param mixed $points     The array of points to process.
     * @param array $result     The array to store the result. Defaults to an empty array.
     * @param int $sizeOfPoints The total number of points. Defaults to 0.
     * @param string $key       The key to identify each point. Defaults to 'key'.
     *
     * @return mixed The ordered points based on the Nearest Neighbor Algorithm.
     *
     * @throws Exception If there is an error during the distance calculation process.
     *
     * @author Karam Mustafa
     */
    public function nearestNeighborAlgorithm(mixed $points, array $result = [], int $sizeOfPoints = 0, string $key = 'key')
    {
        $res = $result;

        // If the result array is empty, append the first point (main point) to it.
        if (empty($res)) {
            $res[0] = collect($points)->first();

            // Mark the main point as visited.
            $this->pointsAppendedBefore[] = 0;
        }

        // If all points have been visited, return the final result.
        if (count($res) == $sizeOfPoints) {
            return $res;
        }

        // If there are no points, return the result array containing only the main point.
        if (!count($points)) {
            return $res;
        }

        // Initialize the distance with a value greater than the Earth's diameter.
        $distance = 64800 * 2;

        // Variable to store the key of the closest point.
        $pointKeyToPush = '';

        // Get the last point in the result array.
        $lastPoint = $res[array_key_last($res)];

        // Iterate through each point to find the closest point to the last point in the result array.
        foreach ($points as $point) {
            // Clear all stored results to ensure clean calculations.
            $this->clearResult();

            // Skip the calculation if the point is the same as the last point.
            if ($point[$key] == $lastPoint[$key]) {
                continue;
            }

            // Calculate the distance between the last point and the current point.
            $distanceCalc = $this->setPoints([
                [$lastPoint[0], $lastPoint[1]],
                [$point[0], $point[1]],
            ])->setOptions(['units' => ['km']])->getDistance(function ($point) {
                return $point->first()['km'];
            });

            // If the calculated distance is less than the previous distance, update the closest point.
            if ($distanceCalc < $distance) {
                $distance = $distanceCalc;

                $pointKeyToPush = $point[$key];
            }
        }

        // If there is only one point, set it as the closest point.
        if (count($points) == 1) {
            $pointKeyToPush = collect($points)->first()[$key];
        }

        // Append the closest point to the result array.
        $res[$pointKeyToPush] = $points[collect($points)->where($key, $pointKeyToPush)->keys()[0]];

        // Mark the closest point as visited.
        $this->pointsAppendedBefore[] = $pointKeyToPush;

        // Assign the calculated distance to it.
        // $res[$pointKeyToPush]['distance'] = $distance;

        // Get the new points array without the visited points.
        $points = collect($points)->whereNotIn($key, $this->pointsAppendedBefore);

        // Recursively call the algorithm until all points are visited.
        return $this->nearestNeighborAlgorithm($points, $res, $sizeOfPoints, $key);
    }

    /**
     * this function will go through each point, and add the key to it.
     *
     * @return void
     *
     * @author Karam Mustafa
     */
    private function resolveKeyForEachPoint()
    {
        foreach ($this->getPoints() as $index => $point) {
            $this->updatePoint($index, function ($p) use ($index) {
                return [$p[0], $p[1], 'key' => $index + 1];
            });
        }
    }
}
