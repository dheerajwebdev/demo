<?php
/**
 * Expansion module for Craft CMS 3.x
 *
 * A utility module for business logic and additional functionality.
 *
 * @link      https://simplygoodwork.com
 * @copyright Copyright (c) 2022 Good Work
 */

namespace modules\expansion;

use craft\log\MonologTarget;
use modules\expansion\assetbundles\expansion\ExpansionAsset;
use modules\expansion\services\ExpansionService as ExpansionServiceService;
use modules\expansion\variables\ExpansionVariable;
use modules\expansion\twigextensions\ExpansionTwigExtension;

use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\TemplateEvent;
use craft\i18n\PhpMessageSource;
use craft\web\View;
use craft\console\Application as ConsoleApplication;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use Monolog\Formatter\LineFormatter;

use Psr\Log\LogLevel;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\log\Logger;
/**
 * Class Expansion
 *
 * @author    Good Work
 * @package   Expansion
 * @since     1.0.0
 *
 * @property  ExpansionServiceService $expansionService
 */
class Expansion extends Module
{
    // Static Properties
    // =========================================================================

    /**
     * @var Expansion
     */
    public static $instance;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('@modules/expansion', $this->getBasePath());
        $this->controllerNamespace = 'modules\expansion\controllers';

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id.'*'])) {
            $i18n->translations[$id] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => '@modules/expansion/translations',
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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$instance = $this;

        if (Craft::$app->getRequest()->getIsCpRequest()) {
            Event::on(
                View::class,
                View::EVENT_BEFORE_RENDER_TEMPLATE,
                function (TemplateEvent $event) {
                    try {
                        Craft::$app->getView()->registerAssetBundle(ExpansionAsset::class);
                    } catch (InvalidConfigException $e) {
                        Craft::error(
                            'Error registering AssetBundle - '.$e->getMessage(),
                            __METHOD__
                        );
                    }
                }
            );
        }
// * Uncomment line below to register custom twig extensions
//        Craft::$app->view->registerTwigExtension(new ExpansionTwigExtension());


        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'modules\expansion\console\controllers';
        }

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'expansion/default';
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['cpActionTrigger1'] = 'expansion/default/do-something';
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('expansion', ExpansionVariable::class);
            }
        );

        // Register a custom log target, keeping the format as simple as possible.
        Craft::getLogger()->dispatcher->targets[] = new MonologTarget([
            'name' => 'expansion',
            'categories' => ['modules\expansion\*'],
            'level' => LogLevel::INFO,
            'logContext' => false,
            'allowLineBreaks' => false,
            'formatter' => new LineFormatter(
                format: "%datetime% [%level_name%] from %extra.yii_category%: %message%\n",
                dateFormat: 'Y-m-d H:i:s',
            ),
        ]);

        Craft::getLogger()->dispatcher->targets[] = new MonologTarget([
            'name' => 'expansion-errors',
            'categories' => ['modules\expansion\*'],
            'level' => LogLevel::ERROR,
            'logContext' => true,
            'allowLineBreaks' => true,
            'formatter' => new LineFormatter(
                format: "%datetime% [%level_name%] from %extra.yii_category%: %message%\n",
                dateFormat: 'Y-m-d H:i:s',
            ),
        ]);
    }


    // Protected Methods
    // =========================================================================
}
