<?php

namespace DiamondDev\GeographicalCalculator\Traits;

/**
 * Trait AngleStorage.
 *
 * This trait provides methods to store and retrieve angles.
 * It includes methods to set and get the sine and cosine values and to clear all stored angles.
 *
 * @author Diamond Mubaarak
 */
trait AngleStorage
{
    /**
     * @author Karam Mustafa
     *
     * @var array
     */
    private array $longs = [];

    /**
     * sin value.
     *
     * @author Karam Mustafa
     *
     * @var int|float|string
     */
    private int|float|string $sin;

    /**
     * cos value.
     *
     * @author Karam Mustafa
     *
     * @var int|float|string
     */
    private int|float|string $cos;

    /**
     * @return int|float|string
     *
     * @author Karam Mustafa
     */
    public function getSin()
    {
        return $this->sin;
    }

    /**
     * @param int|float|string $sin
     *
     * @return $this
     *
     * @author Karam Mustafa
     */
    public function setSin(int|float|string $sin)
    {
        $this->sin = $sin;

        return $this;
    }

    /**
     * @return int|float|string
     *
     * @author Karam Mustafa
     */
    public function getCos()
    {
        return $this->cos;
    }

    /**
     * @return array
     *
     * @author Karam Mustafa
     */
    public function getLongs()
    {
        return $this->longs;
    }

    /**
     * @param $val
     *
     * @return $this
     *
     * @author Karam Mustafa
     */
    public function setLongitude($val)
    {
        $this->longs[] = $val;

        return $this;
    }

    /**
     * @param int|float|string $cos
     *
     * @return $this
     *
     * @author Karam Mustafa
     */
    public function setCos(int|float|string $cos)
    {
        $this->cos = $cos;

        return $this;
    }

    /**
     * clear all stored angles.
     *
     * @author Karam Mustafa
     */
    public function clearAngles()
    {
        $this->setSin('');
        $this->setCos('');
        $this->clearLongs();
    }

    /**
     * clear Longs.
     *
     * @author Karam Mustafa
     */
    private function clearLongs()
    {
        $this->longs = [];
    }
}
