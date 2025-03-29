<?php
/**
 * Expansion module for Craft CMS 3.x
 *
 * A utility module for business logic and additional functionality.
 *
 * @link      https://simplygoodwork.com
 * @copyright Copyright (c) 2022 Good Work
 */

namespace modules\expansion\assetbundles\expansion;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Good Work
 * @package   Expansion
 * @since     1.0.0
 */
class ExpansionAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@modules/expansion/assetbundles/expansion/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Expansion.js',
        ];

        $this->css = [
            'css/Expansion.css',
        ];

        parent::init();
    }
}
