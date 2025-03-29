<?php
/**
 * Dev Blocks module for Craft CMS 3.x
 *
 * Helper for developing blocks
 *
 * @link      https://simplygoodwork.com
 * @copyright Copyright (c) 2021 Chris Rowe
 */

namespace modules\runwaymodule\controllers;

use craft\helpers\FileHelper;
use craft\helpers\Json;
use modules\runwaymodule\RunwayModule;

use Craft;
use craft\web\Controller;
use yii\helpers\Markdown;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your module’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Chris Rowe
 * @package   RunwayModule
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected int|bool|array $allowAnonymous = ['set-status','get-progress-tracker'];
    public $enableCsrfValidation = false;

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our module's index action URL,
     * e.g.: actions/runway-module/default
     *
     * @return mixed
     */
    public function actionSetStatus()
    {
      $defaultDevFile = FileHelper::normalizePath(Craft::getAlias('@config/runway/_default.json'));
      $request = Craft::$app->getRequest();
      $folder = $request->getBodyParam('folder');
      $item = $request->getBodyParam('item');
      $status = $request->getBodyParam('status');
      $value = $request->getBodyParam('value');
      $devFile = Craft::getAlias('@config') . '/runway/' . $folder . '/'. $item . '.json';

      if (!file_exists($devFile)) {
        $path = pathinfo($devFile);
        if (!file_exists($path['dirname'])) {
          mkdir($path['dirname'], 0777, true);
        }
        copy($defaultDevFile, $devFile);
      }

      $jsonString = file_get_contents($devFile);
      $data = json_decode($jsonString, true);

      $data['status'][$status] = $value ? true : false;

      $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
      file_put_contents($devFile, $updatedJson);

      return $this->asJson([
        'statusSet' => true,
      ]);
    }

    /**
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */
    public function actionUpdateTask(): \yii\web\Response
    {
        $this->requireAdmin();
        $folder = $this->request->getRequiredBodyParam('folder');
        $item = $this->request->getRequiredBodyParam('item');
        $properties = $this->request->getRequiredBodyParam('properties');

        if (array_key_exists('notes', $properties)) {
            $itemNotesPath = Craft::getAlias('@config') . '/runway/' . $folder . '/' . $item . '.md';
            if ($properties['notes'] == "") {
                FileHelper::unlink($itemNotesPath);
            } else {
                FileHelper::writeToFile($itemNotesPath, $properties['notes']);
            }
            return $this->asJson(true);
        }

        return $this->asJson(RunwayModule::$instance->json->setItemProperties($folder, $item, $properties));
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function actionGetState(): \yii\web\Response
    {
        $this->requireAdmin();
        $stateFile = FileHelper::normalizePath(Craft::getAlias('@config/runway/_config.json'));
        $state = Json::decode(file_get_contents($stateFile), true);
        return $this->asJson($state);
    }

    public function actionGetProgressTracker(): \yii\web\Response
    {
        $this->requireAdmin();

        // Loop over each sub-folder to just find json files
        $files = [];
        $path = FileHelper::normalizePath(Craft::getAlias('@config/runway'));
        $folders = RunwayModule::getInstance()->template->getFolders();
        foreach($folders as $folder) {
            $jsonFiles = FileHelper::findFiles(
                $path . DIRECTORY_SEPARATOR . $folder['folder'],
                ['only' => ['*.json']]
            );
            $files = array_merge($files, $jsonFiles);
        }

        // Loop over each file to find allocated hours and percentage complete
        $totalAllocatedHours = 0;
        $totalUsedHours = 0;

        foreach($files as $file) {
            $module = Json::decode(file_get_contents($file), true);
            $allocatedHours = intval($module['estimate']) ?? 0;
            $percentComplete = intval($module['status']) ?? 0;
            $usedHours = 0;

            if ($allocatedHours > 0 && $percentComplete > 0) {
                $usedHours = $allocatedHours * ($percentComplete / 100);
            }

            $totalAllocatedHours += $allocatedHours;
            $totalUsedHours += $usedHours;
        }

        $config = RunwayModule::getInstance()->json->getGlobalStats();

        $progress = [
            'totalEstimatedHours' => $config['totalEstimatedHours'] ?? 0,
            'totalAllocatedHours' => $totalAllocatedHours,
            'totalUsedHours' => $totalUsedHours,
            'timeline' => $config['timeline'],
        ];

        return $this->asJson($progress);
    }
}
