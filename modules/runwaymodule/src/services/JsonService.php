<?php
    /**
     * Dev Blocks module for Craft CMS 3.x
     *
     * Helper for developing blocks
     *
     * @link      https://simplygoodwork.com
     * @copyright Copyright (c) 2021 Chris Rowe
     */

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
    class JsonService extends Component
    {

        // Public Methods
        // =========================================================================

        public function getGlobalStats(): string|array
        {
            $globalStatsPath = FileHelper::normalizePath(Craft::getAlias('@config/runway/_config.json'));
            return $this->_getOrCreateFileContents($globalStatsPath);
        }

        /**
         * @param array $stats
         */
        public function setGlobalStats(?array $newStats): string|array
        {
            $globalStatsPath = FileHelper::normalizePath(Craft::getAlias('@config/runway/_config.json'));
            $currentGlobalStats = $this->_getOrCreateFileContents($globalStatsPath);
//            $updatedGlobalStats = new GlobalStats(ArrayHelper::merge($currentGlobalStats, $newStats));

            try {
//                $this->_setFileContents($globalStatsPath, $updatedGlobalStats);
            } catch (Throwable $e) {
                return $e->getMessage();
            }

            return [];
        }

        /**
         * @param string $folder
         * @param string $item
         * @param string $estimate
         * @return mixed|string
         */
        public function setItemProperties(string $folder, string $item, array $props)
        {
            $itemStatusPath = Craft::getAlias('@config') . '/runway/' . $folder . '/' . $item . '.json';
            $currentItemStatus = $this->_getOrCreateFileContents($itemStatusPath);
            $newItemStatus = ArrayHelper::merge($currentItemStatus, $props);

            try {
                $this->_setFileContents($itemStatusPath, $newItemStatus);
            } catch (Throwable $e) {
                return $e->getMessage();
            }

            return $currentItemStatus;
        }

        /**
         * @throws \yii\base\ErrorException
         * @throws Exception
         */
        private function _getOrCreateFileContents(string $path): mixed
        {
            if(!file_exists($path)){
                $defaultFile = FileHelper::normalizePath(Craft::getAlias('@config/runway/_default.json'));
                FileHelper::writeToFile($path, file_get_contents($defaultFile));
            }
            return Json::decode(file_get_contents($path));
        }

        /**
         * @throws \yii\base\ErrorException
         * @throws Exception
         */
        private function _setFileContents(string $path, mixed $content): void
        {
            $adjustedPath = $path;

            FileHelper::writeToFile($adjustedPath, Json::encode($content, JSON_PRETTY_PRINT));
        }
    }
