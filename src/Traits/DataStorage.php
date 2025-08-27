<?php

namespace DiamondDev\GeographicalCalculator\Traits;

/**
 * Trait DataStorage.
 *
 * This trait provides methods to store, retrieve, and manipulate data within a class.
 * It includes functionalities for managing options, results, and a custom local storage.
 *
 * @author  Karam Mustafa
 */
trait DataStorage
{
    /**
     * Stores key-value pairs for sharing data across methods and classes.
     *
     * @var array
     */
    private array $localStorage = [];

    /**
     * Stores the results of various operations.
     *
     * @var array
     */
    private array $result = [];

    /**
     * Stores options for configuring various operations.
     *
     * @var array
     */
    private array $options = [];

    /**
     * Retrieves the 'options' array or a specific option by key.
     *
     * @param mixed|null $key The key of the option to retrieve.
     *
     * @return array The 'options' array or the specific option.
     */
    public function getOptions(mixed $key = null)
    {
        return $this->options[$key] ?? $this->options;
    }

    /**
     * Sets the 'options' array.
     *
     * @param array $options The options to set.
     *
     * @return $this The current instance for method chaining.
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Retrieves the result array, optionally processed by a callback.
     *
     * @param callable|null $callback Optional callback to process the result.
     *
     * @return mixed The result array or the processed result.
     */
    public function getResult(callable $callback = null)
    {
        return isset($callback)
            ? $callback(collect($this->result))
            : $this->result;
    }

    /**
     * Retrieves a specific result by key or the entire result array.
     *
     * @param string|null $key The key of the result to retrieve.
     *
     * @return mixed The specific result or the entire result array.
     */
    public function getResultByKey(string $key = null)
    {
        return $this->result[$key] ?? $this->result;
    }

    /**
     * Merges new results into the existing result array.
     *
     * @param mixed $result The result to merge.
     *
     * @return $this The current instance for method chaining.
     */
    public function setResult(mixed $result)
    {
        $this->result = array_merge($this->result, $result);

        return $this;
    }

    /**
     * Appends a value to an array stored under a specific key in the local storage.
     *
     * @param string $key The key under which the value is stored.
     * @param mixed $value The value to append.
     *
     * @return $this The current instance for method chaining.
     */
    public function appendToStorage(string $key, mixed $value)
    {
        if (!isset($this->localStorage[$key])) {
            $this->localStorage[$key] = [];
        }

        $this->localStorage[$key][] = $value;

        return $this;
    }

    /**
     * Clears all stored results.
     *
     * @return $this The current instance for method chaining.
     */
    public function clearStoredResults()
    {
        $this->result = [];

        return $this;
    }

    /**
     * Retrieves a value from the local storage by key or the entire local storage array.
     *
     * @param mixed|null $key The key of the value to retrieve.
     *
     * @return mixed The value associated with the key or the entire local storage array.
     */
    public function getFromStorage(mixed $key = null)
    {
        if (is_array($key)) {
            return $this->getCustomKeysFromStorage($key);
        }

        return $this->localStorage[$key] ?? $this->localStorage;
    }

    /**
     * Checks if a specific key exists in the local storage.
     *
     * @param mixed|null $key The key to check.
     *
     * @return bool True if the key exists, false otherwise.
     */
    public function inStorage(mixed $key = null)
    {
        return isset($this->localStorage[$key]);
    }

    /**
     * /**
     * Retrieves specific keys from the local storage.
     *
     * @param array $keys The keys to retrieve.
     *
     * @return array An array of key-value pairs from the local storage.
     */
    public function getCustomKeysFromStorage(array $keys)
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->getFromStorage($key);
        }

        return $result;
    }

    /**
     * /**
     * Sets a value in the local storage under a specific key.
     *
     * @param string $key The key under which the value is stored.
     * @param mixed $value The value to store.
     *
     * @return $this The current instance for method chaining.
     */
    public function setInStorage(string $key, mixed $value)
    {
        $this->localStorage[$key] = $value;

        return $this;
    }

    /**
     * Clears all data from the local storage.
     *
     * @return $this The current instance for method chaining.
     */
    public function clearStorage()
    {
        $this->localStorage = [];

        return $this;
    }

    /**
     * Removes specific keys from the local storage.
     *
     * @param mixed $keys The keys to remove.
     *
     * @return $this The current instance for method chaining.
     */
    public function removeFromStorage(...$keys)
    {
        foreach ($keys as $key) {
            if (isset($this->localStorage[$key])) {
                unset($this->localStorage[$key]);
            }
        }

        return $this;
    }

    /**
     * Resolves the result by optionally processing it with a callback.
     *
     * @param mixed $result The result to process.
     * @param callable|null $callback Optional callback to process the result.
     *
     * @return mixed The processed result or the original result.
     */
    private function resolveCallbackResult(mixed $result, ?callable $callback)
    {
        return isset($callback)
            ? $callback(collect($result))
            : $result;
    }
}
