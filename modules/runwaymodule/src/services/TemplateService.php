<?php

namespace modules\runwaymodule\services;

use craft\helpers\ArrayHelper;
use craft\helpers\FileHelper;
use craft\helpers\Json;

use Craft;
use craft\base\Component;
use Throwable;
use yii\base\Exception;

/**
 *
 */
class TemplateService extends Component
{

    // Public Methods
    // =========================================================================

    public function getFolders(): array
    {
        $configFile = FileHelper::normalizePath(Craft::getAlias('@config/runway/_config.json'));

        if (file_exists($configFile)) {
            $config = Json::decode(file_get_contents($configFile), true);

            if (isset($config['folders'])) {
                return $config['folders'];
            }
        }
        return [];
    }
}
