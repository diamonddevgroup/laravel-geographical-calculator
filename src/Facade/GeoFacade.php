<?php

declare(strict_types=1);

namespace DiamondDev\GeographicalCalculator\Facade;

use DiamondDev\GeographicalCalculator\Classes\Geo;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Geo setPoint(array $point)
 * @method static Geo setPoints(array $points)
 * @method static Geo setOptions(array $options)
 * @method static Geo setMainPoint(array $point)
 * @method static Geo setDiameter(int $diameter)
 * @method static Geo clearResult()
 * @method static Geo getDistance($callback)
 * @method static Geo getCenter($callback)
 * @method static Geo allFeatures($callback)
 * @method static Geo getClosest()
 * @method static Geo getFarthest()
 * @method static Geo getOrderByNearestNeighbor()
 * @method static Geo isInArea()
 */
class GeoFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GeoFacade';
    }
}
