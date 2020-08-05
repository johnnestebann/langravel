<?php

namespace Johnnestebann\Langravel;

use Illuminate\Support\Facades\Route;

trait Langravel
{
    protected function mapLocalizedWebRoutes()
    {
        foreach (config('langravel.supportedLocales') as $locale) {
            if ($locale == config('langravel.defaultLocale')) {
                Route::middleware(['web', 'langravel'])
                    ->namespace($this->namespace)
                    ->group($this->getBasePathForWebRoutes($locale));
            }

            Route::prefix($locale)
                ->name("{$locale}.")
                ->middleware(['web', 'langravel'])
                ->namespace($this->namespace)
                ->group($this->getBasePathForWebRoutes($locale));
        }
    }

    /**
     * It gets the web routes file path.
     *
     * If the useTranslatedUrls config option is set to true then
     * it returns the path to the web routes file for the given locale.
     */
    protected function getBasePathForWebRoutes($locale)
    {
        if (config('langravel.useTranslatedUrls') == true) {
            return base_path("routes/{$locale}.web.php");
        }

        return base_path('routes/langravel.web.php');
    }
}
