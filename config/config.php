<?php

/*
 * LaraLens Configuration.
 */
return [
    'prefix' => env('LARALENS_WEB_ENABLED', 'laralens'), // URL prefix (default=laralens)
    'middleware' => ['web'], // middleware to use (default=web)
    'web-enabled' => env('LARALENS_WEB_ENABLED', 'off') // Activate web view (default=off)
];
