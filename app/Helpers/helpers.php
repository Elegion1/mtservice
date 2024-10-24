<?php

if (!function_exists('updateLocaleInUrl')) {

    function updateLocaleInUrl($newLocale)
    {
        $currentUrl = request()->url(); // Get the current URL without query parameters
        $segments = request()->segments(); // Get all URL segments

        // Check if the first segment is a locale and replace it
        if (in_array($segments[0], config('app.available_locales'))) {
            $segments[0] = $newLocale; // Update the locale segment
        } else {
            array_unshift($segments, $newLocale); // Add the new locale as the first segment
        }

        return url(implode('/', $segments)); // Rebuild the URL and return
    }
}
