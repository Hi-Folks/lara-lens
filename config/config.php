<?php

/*
 * LaraLens Configuration.
 */
return [
    'prefix' => env('LARALENS_PREFIX', 'laralens'), // URL prefix (default=laralens)
    'middleware' => env('LARALENS_MIDDLEWARE', ['web']), // middleware to use (default=web)
    'web-enabled' => env('LARALENS_WEB_ENABLED', 'off') // Activate web view (default=off)
];
