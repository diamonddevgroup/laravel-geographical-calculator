<?php

namespace DiamondDev\GeographicalCalculator\Interfaces;

interface GeoInterface
{
    /**
     * @param array $point
     *
     * @author Karam Mustafa
     */
    public function setPoint(array $point);

    /**
     * Finding the distance of points using several given coordinate points.
     *
     * @author Karam Mustafa
     */
    public function getDistance();

    /**
     * Finding the center of points using several given coordinate points.
     *
     * @author Karam Mustafa
     */
    public function getCenter();

    /**
     * clear all stored results.
     *
     * @return GeoInterface
     *
     * @author Karam Mustafa
     */
    public function clearResult();

    /**
     * get all package features.
     *
     * @return array
     *
     * @author Karam Mustafa
     */
    public function allFeatures();
}
