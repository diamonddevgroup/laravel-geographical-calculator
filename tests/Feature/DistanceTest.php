<?php

namespace DiamondDev\GeographicalCalculator\Tests\Feature;

use DiamondDev\GeographicalCalculator\Classes\Geo;
use DiamondDev\GeographicalCalculator\Interfaces\GeoInterface;
use Exception;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class DistanceTest extends OrchestraTestCase
{
    /**
     * Test if the distance is correctly calculated for the given coordinates.
     *
     * @return void
     *
     * @throws Exception
     */
    public function test_distance_is_correct()
    {
        $result = $this->newGeoInstance()->setPoint([22, 37])
            ->setOptions(['units' => ['km']])
            ->setPoint([33, 40])
            ->getDistance();

        $this->assertEquals([
            '1-2' => ['km' => 1258.1691302281708],
        ], $result);
    }

    /**
     * Get a clean instance of the Geo class.
     *
     * @return Geo|GeoInterface
     */
    public function newGeoInstance()
    {
        return (new Geo())->clearResult();
    }
}
