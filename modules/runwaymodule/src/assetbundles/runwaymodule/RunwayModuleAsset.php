<?php
/**
 * Dev Blocks module for Craft CMS 3.x
 *
 * Helper for developing blocks
 *
 * @link      https://simplygoodwork.com
 * @copyright Copyright (c) 2021 Chris Rowe
 */

namespace modules\runwaymodule\assetbundles\runwaymodule;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * RunwayModuleAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    Chris Rowe
 * @package   RunwayModule
 * @since     1.0.0
 */
class RunwayModuleAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {

        $this->sourcePath = "@modules/runwaymodule/web/dist";

        // define the dependencies
//        $this->depends = [
//            CpAsset::class,
//        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
        ];
//
//        $this->css = [
//            'css/RunwayModule.css',
//        ];
        parent::init();

    }
}
