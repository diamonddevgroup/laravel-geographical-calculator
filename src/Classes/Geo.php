<?php

namespace DiamondDev\GeographicalCalculator\Classes;

use DiamondDev\GeographicalCalculator\Abstracts\AbstractGeo;
use DiamondDev\GeographicalCalculator\Interfaces\GeoInterface;
use DiamondDev\GeographicalCalculator\Traits\AngleStorage;
use DiamondDev\GeographicalCalculator\Traits\Areas;
use DiamondDev\GeographicalCalculator\Traits\DataStorage;
use DiamondDev\GeographicalCalculator\Traits\Debugger;
use DiamondDev\GeographicalCalculator\Traits\DiametersStorage;
use DiamondDev\GeographicalCalculator\Traits\Distances;
use DiamondDev\GeographicalCalculator\Traits\Formatter;
use DiamondDev\GeographicalCalculator\Traits\Looper;
use DiamondDev\GeographicalCalculator\Traits\Ordering;
use DiamondDev\GeographicalCalculator\Traits\PointsStorage;
use Exception;

/**
 * Class Geo.
 *
 * This class provides geographical calculations such as distance and center point calculations.
 * It uses various traits to extend its functionality.
 *
 * @author Diamond Mubaarak
 */
class Geo extends AbstractGeo implements GeoInterface
{
    use DataStorage;
    use PointsStorage;
    use AngleStorage;
    use DiametersStorage;
    use Formatter;
    use Debugger;
    use Looper;
    use Areas;
    use Distances;
    use Ordering;

    /**
     * Clear the results stored in the class.
     *
     * This method checks if the class has a property called `result` and clears various
     * stored results and points if it exists.
     *
     * @return $this
     */
    public function clearResult()
    {
        if (property_exists(__CLASS__, 'result')) {
            $this->clearStorage();
            $this->clearPoints();
            $this->clearStoredResults();
            $this->clearAngles();
        }

        return $this;
    }

    /**
     * Execute all available features and store their results.
     *
     * This method runs each feature, stores its result, and then clears the result to
     * prepare for the next feature.
     * Finally, it returns the combined results.
     *
     * @param callable|null $callback Optional callback function to execute after each feature.
     *
     * @return array The combined results of all features.
     *
     * @throws Exception
     */
    public function allFeatures(callable $callback = null)
    {
        $this->setInStorage('distanceResult', $this->getDistance())
            ->clearStoredResults()
            ->setInStorage('centerResult', $this->getCenter())
            ->clearStoredResults();

        $this->setResult([
            'distance' => $this->getFromStorage('distanceResult'),
            'center' => $this->getFromStorage('centerResult'),
        ]);

        return $this->getResult();
    }
}
