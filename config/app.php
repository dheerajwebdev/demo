<?php
/**
 * Yii Application Config
 *
 * Edit this file at your own risk!
 *
 * The array returned by this file will get merged with
 * vendor/craftcms/cms/src/config/app.php and app.[web|console].php, when
 * Craft's bootstrap script is defining the configuration for the entire
 * application.
 *
 * You can define custom modules and system components, and even override the
 * built-in system components.
 *
 * If you want to modify the application config for *only* web requests or
 * *only* console requests, create an app.web.php or app.console.php file in
 * your config/ folder, alongside this one.
 */

use craft\helpers\App;

return [
    'id' => App::env('CRAFT_APP_ID') ?: 'CraftCMS',
    'modules' => [
        'runway' => [
            'class' => \modules\runwaymodule\RunwayModule::class,
            'components' => [
                'json' => [
                    'class' => 'modules\runwaymodule\services\JsonService',
                ],
                'template' => [
                    'class' => 'modules\runwaymodule\services\TemplateService',
                ],
            ],
        ],
        'expansion' => [
            'class' => \modules\expansion\Expansion::class,
            'components' => [
                'expansionService' => [
                    'class' => 'modules\expansion\services\ExpansionService',
                ],
            ],
        ],
    ],
    'components' => [
      'deprecator' => [
          'throwExceptions' => YII_DEBUG,
      ],
    ],
    'bootstrap' => [],
];
