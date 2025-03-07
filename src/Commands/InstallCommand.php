<?php

namespace DiamondDev\GeographicalCalculator\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    public string $packageLink = 'https://github.com/karam-mustafa/laravel-geographical-calculator';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install all laravel-geographical-calculator package dependencies';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        system('php artisan vendor:publish --provider="DiamondDev\GeographicalCalculator\Providers\GeographicalCalculatorServiceProviders"');
        system('php artisan  vendor:publish --tag=geographical-calculator-config');

        $this->info('<info> Install the dependencies was succeed</info>');

        if ($this->confirm('Would you like to show some love by starring the repo?', true)) {
            if (PHP_OS_FAMILY === 'Darwin') {
                exec("open $this->packageLink");
            }
            if (PHP_OS_FAMILY === 'Linux') {
                exec("xdg-open $this->packageLink");
            }
            if (PHP_OS_FAMILY === 'Windows') {
                exec("start $this->packageLink");
            }

            $this->line('Thank you!');
        }
    }
}
