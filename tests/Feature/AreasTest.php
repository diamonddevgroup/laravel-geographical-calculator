<?php

namespace DiamondDev\GeographicalCalculator\Tests\Feature;

use DiamondDev\GeographicalCalculator\Classes\Geo;
use DiamondDev\GeographicalCalculator\Interfaces\GeoInterface;
use Exception;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class AreasTest extends OrchestraTestCase
{
    /**
     * Test if the center is correctly calculated for the given coordinates.
     *
     * @return void
     * @throws Exception
     */
    public function test_center()
    {
        $result = $this->newGeoInstance()->setPoint([22, 37])
            ->setPoint([33, 40])
            ->getCenter();

        $this->assertEquals([
            'lat' => 27.508023496931166,
            'long' => 38.424795502212234,
        ], $result);
    }

    /**
     * Test if a given point is within a custom area, based on the main point and diameter.
     *
     * @return void
     * @throws Exception
     */
    public function test_if_given_point_is_in_custom_area()
    {
        // The result must be true
        $result = $this->newGeoInstance()->setMainPoint([22, 37])
            ->setDiameter(1000)
            ->setPoint([33, 40])
            ->isInArea();

        $this->assertTrue($result);

        // The result must be false
        $result = $this->newGeoInstance()->setMainPoint([22, 37])
            ->setDiameter(2000)
            ->setPoint([33, 40])
            ->isInArea();

        $this->assertFalse($result);
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
