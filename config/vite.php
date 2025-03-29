<?php
/**
 * Vite plugin for Craft CMS 3.x
 *
 * Allows the use of the Vite.js next generation frontend tooling with Craft CMS
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2021 nystudio107
 */

use craft\helpers\App;

return [
    'useDevServer' => App::env('ENVIRONMENT') === 'dev',
    'manifestPath' => '@webroot/dist/manifest.json',
    'devServerPublic' => App::env('VITE_DEVSERVER_PUBLIC') ?? 'http://localhost:3002/',
    'serverPublic' => App::env('DEFAULT_SITE_URL') . '/dist/',
    'errorEntry' => 'src/main.js',
    'cacheKeySuffix' => '',
    'devServerInternal' => App::env('VITE_DEVSERVER_INTERNAL') ?? 'http://host.docker.internal:3002/',
    'checkDevServer' => false,
    'includeReactRefreshShim' => false,
    'includeModulePreloadShim' => true,
    'criticalPath' => '@webroot/dist/criticalcss',
    'criticalSuffix' =>'_critical.min.css',
];