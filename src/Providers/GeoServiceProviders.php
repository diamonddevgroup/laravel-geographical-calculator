<?php

namespace DiamondDev\GeographicalCalculator\Providers;

use DiamondDev\GeographicalCalculator\Classes\Geo;
use DiamondDev\GeographicalCalculator\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;

class GeoServiceProviders extends ServiceProvider
{
    public function boot()
    {
        $this->registerFacades();
        $this->publishesPackages();
        $this->resolveCommands();
    }

    public function register() {}

    /**
     * register facades dependence's.
     */
    protected function registerFacades()
    {
        $this->app->singleton('GeoFacade', function () {
            return new Geo();
        });
    }

    /**
     * @desc   publish files
     *
     * @author Karam Mustafa
     */
    protected function publishesPackages()
    {
        $this->publishes([
            __DIR__ . '/../Config/geographical_calculator.php' => config_path('geographical_calculator.php'),
        ], 'geographical-calculator-config');
    }

    /**
     * @author Karam Mustafa
     */
    private function resolveCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}
