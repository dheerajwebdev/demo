<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 *
 * @see \craft\config\GeneralConfig
 */

use craft\config\GeneralConfig;
use craft\helpers\App;

$isDev = App::env('ENVIRONMENT') === 'dev';
$isProd = App::env('ENVIRONMENT') === 'production';

// https://craftcms.com/docs/4.x/config/config-settings.html

return GeneralConfig::create()
    ->aliases([
        '@webroot' => dirname(__DIR__) . '/web',
    ])
    ->allowAdminChanges($isDev)
    ->devMode($isDev)
    ->disallowRobots(!$isProd)
    ->errorTemplatePrefix('_errors/')
    ->omitScriptNameInUrls(true)
    ->privateTemplateTrigger('')
    ->sendPoweredByHeader(false)
    ->timezone('America/Chicago')
    ->testToEmailAddress($isDev ? 'dev@simplygoodwork.com' : false)
    ->transformGifs(false)
    ->upscaleImages(false);
