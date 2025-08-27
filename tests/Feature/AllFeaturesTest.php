<?php

namespace DiamondDev\GeographicalCalculator\Tests\Feature;

use DiamondDev\GeographicalCalculator\Classes\Geo;
use DiamondDev\GeographicalCalculator\Interfaces\GeoInterface;
use Exception;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class AllFeaturesTest extends OrchestraTestCase
{
    /**
     * Test the retrieval of all features (distance and center) for given coordinates.
     *
     * @return void
     *
     * @throws Exception
     */
    public function test_get_all_feature()
    {
        $distance = $this->newGeoInstance()
            ->setOptions(['units' => ['km', 'mile', 'm', 'cm']])
            ->setPoint([40.92945, 14.44301])
            ->setPoint([40.92918, 14.44339])
            ->getDistance();

        $center = $this->newGeoInstance()
            ->setOptions(['units' => ['km', 'mile', 'm', 'cm']])
            ->setPoint([40.92945, 14.44301])
            ->setPoint([40.92918, 14.44339])
            ->getCenter();

        $all = $this->newGeoInstance()
            ->setOptions(['units' => ['km', 'mile', 'm', 'cm']])
            ->setPoint([40.92945, 14.44301])
            ->setPoint([40.92918, 14.44339])
            ->allFeatures();

        $this->assertEquals([
            'distance' => $distance,
            'center' => $center,
        ], $all);
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
