<?php

namespace DiamondDev\GeographicalCalculator\Tests\Feature;

use DiamondDev\GeographicalCalculator\Classes\Geo;
use DiamondDev\GeographicalCalculator\Interfaces\GeoInterface;
use Exception;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class OrderingTest extends OrchestraTestCase
{
    /**
     * Test the closest point of a set of points.
     *
     * @return void
     *
     * @throws Exception
     */
    public function test_closest_point_of_set_points()
    {
        $result = $this->newGeoInstance()
            ->setMainPoint([40.9171863, 14.1632638])
            ->setPoint([40.92945, 14.44301])
            ->setPoint([40.92918, 14.44339])
            ->getClosest();

        $this->assertEquals([
            // The key is the index of points insertion.
            1 => [
                40.92918,
                14.44339,
            ],
        ], $result);

        $result = $this->newGeoInstance()
            ->setMainPoint([40.9171863, 14.1632638])
            ->setPoint([40.92918, 14.44339])
            ->setPoint([40.92945, 14.44301])
            ->getClosest();

        // Now the closest point index should be 0.
        $this->assertEquals([
            0 => [
                40.92918,
                14.44339,
            ],
        ], $result);
    }

    /**
     * Test the farthest point of a set of points.
     *
     * @return void
     *
     * @throws Exception
     */
    public function test_farthest_point_of_set_points()
    {
        $result = $this->newGeoInstance()
            ->setMainPoint([40.9171863, 14.1632638])
            ->setPoint([40.92945, 14.44301])
            ->setPoint([40.92918, 14.44339])
            ->getFarthest();

        $this->assertEquals([
            // The key is the index of points insertion.
            0 => [
                40.92945,
                14.44301,
            ],
        ], $result);

        $result = $this->newGeoInstance()
            ->setMainPoint([40.9171863, 14.1632638])
            ->setPoint([40.92918, 14.44339])
            ->setPoint([40.92945, 14.44301])
            ->getFarthest();

        // Now the farthest point index should be 1.
        $this->assertEquals([
            1 => [
                40.92945,
                14.44301,
            ],
        ], $result);
    }

    /**
     * Test the order of points using the nearest neighbor algorithm.
     *
     * @return void
     *
     * @throws Exception
     */
    public function test_order_by_nearest_neighbor_algorithm()
    {
        $result = $this->newGeoInstance()
            ->setMainPoint([40.9171863, 14.1632638])
            ->setPoint([40.92945, 14.44301])
            ->setPoint([40.92918, 14.44339])
            ->getOrderByNearestNeighbor();

        // This data is already ordered, so we depend on the returned keys to test the results.
        $this->assertEquals([
            [0, 1, 2],
        ], [collect($result)->keys()->toArray()]);

        // Re-implement the same points.
        // Arrange the points in a slightly different order.
        // The order of the points should remain correct.

        $result = $this->newGeoInstance()
            ->setMainPoint([40.9171863, 14.1632638])
            ->setPoint([40.92918, 14.44339])
            ->setPoint([40.92945, 14.44301])
            ->getOrderByNearestNeighbor();

        $this->assertEquals([
            [0, 2, 1],
        ], [collect($result)->keys()->toArray()]);
    }

    /**
     * Get a clean instance of the Geo class.
     *
     * @return Geo|GeoInterface
     *
     * @author Karam Mustafa
     */
    public function newGeoInstance()
    {
        return (new Geo())->clearResult();
    }
}
