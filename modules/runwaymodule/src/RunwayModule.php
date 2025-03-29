<?php
/**
 * Dev Blocks module for Craft CMS 3.x
 *
 * Helper for developing blocks
 *
 * @link      https://simplygoodwork.com
 * @copyright Copyright (c) 2021 Chris Rowe
 */

namespace modules\runwaymodule;

use craft\helpers\App;
use craft\log\MonologTarget;
use modules\runwaymodule\assetbundles\runwaymodule\RunwayModuleAsset;
use modules\runwaymodule\variables\RunwayModuleVariable;

use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\i18n\PhpMessageSource;
use craft\web\View;
use craft\web\UrlManager;
use craft\web\twig\variables\Cp;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUrlRulesEvent;

use Monolog\Formatter\LineFormatter;
use nystudio107\pluginvite\services\VitePluginService;
use Psr\Log\LogLevel;
use yii\base\Event;
use yii\base\Module;
use yii\log\Logger;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Chris Rowe
 * @package   RunwayModule
 * @since     1.0.0
 *
 */
class RunwayModule extends Module
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this module class so that it can be accessed via
     * RunwayModule::$instance
     *
     * @var RunwayModule
     */
    public static $instance;

    public bool $hasCpSection = true;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        $config['components'] = [
            'vite' => [
                'class' => VitePluginService::class,
                'assetClass' => RunwayModuleAsset::class,
                'useDevServer' => App::env('VITE_PLUGIN_DEVSERVER'),
                'manifestPath' => '@modules/runwaymodule/web/dist/manifest.json',
                'devServerPublic' => 'https://localhost:3002/',
                'serverPublic' => App::env('DEFAULT_SITE_URL'),
                'errorEntry' => 'web/src/app.js',
                'devServerInternal' => 'https://localhost:3002/',
                'checkDevServer' => false,
            ],
        ];

        Craft::setAlias('@modules/runwaymodule', $this->getBasePath());
        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'modules\runwaymodule\console\controllers';
        } else {
            $this->controllerNamespace = 'modules\runwaymodule\controllers';
        }

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id.'*'])) {
            $i18n->translations[$id] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => '@modules/runwaymodule/translations',
                'forceTranslation' => true,
                'allowOverrides' => true,
            ];
        }

        // Base template directory
        Event::on(View::class, View::EVENT_REGISTER_CP_TEMPLATE_ROOTS, function (RegisterTemplateRootsEvent $e) {
            if (is_dir($baseDir = $this->getBasePath().DIRECTORY_SEPARATOR.'templates')) {
                $e->roots[$this->id] = $baseDir;
            }
        });

        // Set this as the global instance of this module class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    /**
     * Set our $instance static property to this class so that it can be accessed via
     * RunwayModule::$instance
     *
     * Called after the module class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
      parent::init();
      self::$instance = $this;

        // Load our AssetBundle
//        if (Craft::$app->getRequest()->getIsCpRequest()) {
//            Event::on(
//                View::class,
//                View::EVENT_BEFORE_RENDER_TEMPLATE,
//                function (TemplateEvent $event) {
//                    try {
//                        Craft::$app->getView()->registerAssetBundle(RunwayModuleAsset::class);
//                    } catch (InvalidConfigException $e) {
//                        Craft::error(
//                            'Error registering AssetBundle - '.$e->getMessage(),
//                            __METHOD__
//                        );
//                    }
//                }
//            );
//        }

        // Register services as components
        $this->setComponents([
            'json' => \modules\runwaymodule\services\JsonService::class,
            'template' => \modules\runwaymodule\services\TemplateService::class,
        ]);

        Event::on(
            View::class,
            View::EVENT_REGISTER_CP_TEMPLATE_ROOTS,
            function(RegisterTemplateRootsEvent $event) {
                $event->roots['runway'] = __DIR__ . '/templates';
//                $event->roots['frontend'] = '@templates';
            }
        );

        // Register our site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['runway/set-status'] = 'runwaymodule/default/set-status';
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['runway'] = ['template' => 'runway/runway'];
                $event->rules['runway/<folder>/<block>'] = ['template' => 'runway/runway/_item'];
            }
        );

        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function(RegisterCpNavItemsEvent $event) {
                $event->navItems[] = [
                    'url' => 'runway',
                    'label' => 'Runway',
                    'icon' => '@modules/runwaymodule/icon.svg',
                ];
            }
        );


        // Register our variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('runwayModule', [
                    'class' => RunwayModuleVariable::class,
                    'viteService' => $this->vite,
                ]);
            }
        );

        // Register a custom log target, keeping the format as simple as possible.
        Craft::getLogger()->dispatcher->targets[] = new MonologTarget([
            'name' => 'runway-module',
            'categories' => ['runway-module'],
            'level' => LogLevel::INFO,
            'logContext' => false,
            'allowLineBreaks' => false,
            'formatter' => new LineFormatter(
                format: "%datetime% %message%\n",
                dateFormat: 'Y-m-d H:i:s',
            ),
        ]);

//        Craft::getLogger()->log("Runway module loaded!", Logger::LEVEL_INFO, 'runway-module');

    }

    // Protected Methods
    // =========================================================================
}
