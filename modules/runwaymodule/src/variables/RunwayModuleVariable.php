<?php
/**
 * Dev Blocks module for Craft CMS 3.x
 *
 * Helper for developing blocks
 *
 * @link      https://simplygoodwork.com
 * @copyright Copyright (c) 2021 Chris Rowe
 */

namespace modules\runwaymodule\variables;

use craft\helpers\FileHelper;
use craft\helpers\Json;
use craft\helpers\Template;
use craft\web\View;
use Exception;
use modules\runwaymodule\RunwayModule;

use Craft;
use nystudio107\pluginvite\variables\ViteVariableInterface;
use nystudio107\pluginvite\variables\ViteVariableTrait;
use yii\helpers\Markdown;

/**
 * Runway Variable
 *
 * Craft allows modules to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.RunwayModule }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Chris Rowe
 * @package   RunwayModule
 * @since     1.0.0
 */
class RunwayModuleVariable implements ViteVariableInterface
{
    use ViteVariableTrait;

    // Public Methods
    // =========================================================================

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \yii\base\Exception
     * @throws \Twig\Error\LoaderError
     */
    public function renderTemplate(string $path, array $variables = []): string
    {
// Stash the old template mode, and set it Control Panel template mode
        $oldMode = Craft::$app->view->getTemplateMode();
        try {
            Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_SITE);
        } catch (Exception $e) {
            Craft::error($e->getMessage(), __METHOD__);
        }

        // Render the template with our vars passed in
        try {
            $htmlText = Craft::$app->view->renderTemplate($path, $variables);
        } catch (\Exception $e) {
            $htmlText = 'Error rendering template '.$path.' -> '.$e->getMessage();
            Craft::error(Craft::t('runway', $htmlText), __METHOD__);
        }

        // Restore the old template mode
        try {
            Craft::$app->view->setTemplateMode($oldMode);
        } catch (Exception $e) {
            Craft::error($e->getMessage(), __METHOD__);
        }

        return Template::raw($htmlText);
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \yii\base\Exception
     * @throws \Twig\Error\LoaderError
     */
    public function resolveTemplate(string $path): bool|string
    {
        return Craft::$app->getView()->resolveTemplate($path, View::TEMPLATE_MODE_SITE);
    }

    public function getImages($folder, $item) {
      // TODO: Not loading jpgs for some reason
      $search = './../templates/' . $folder . '/' . $item . '/dev/*.png';
      $images = glob($search);

      foreach ($images as &$image) {
        $image = \Craft::$app->assetManager->getPublishedUrl($image,true);
      }
      return $images;
    }

    public function list($folder)
    {
        $ignore = [
          '.',
          '..',
          '.DS_Store',
          'base.twig',
          'index.twig'
        ];

        $dir = scandir('../templates/' . $folder);
        $plates = array_diff($dir, $ignore);

        $request = Craft::$app->getRequest()->fullPath;
        $designs = array();

        array_walk($plates, function(&$item) use($request, $folder) {

          $defaultDevFile = FileHelper::normalizePath(Craft::getAlias('@config/runway/_default.json'));
          $defaultDev = Json::decode(file_get_contents($defaultDevFile), true);
          $url = "/" . implode('/', array($request,$folder,$item));

          $designs = $this->getImages($folder, $item);
          $statusFile = Craft::getAlias('@config') . '/runway/' . $folder . '/' . $item . '.json';
          $notesFile = Craft::getAlias('@config') . '/runway/' . $folder . '/' . $item . '.md';

          if (file_exists($statusFile)) {
            $itemDev = json_decode(file_get_contents($statusFile), true);
            $status['status'] = $itemDev['status'];
            $status['assigned'] = $itemDev['assigned'];
            $status['estimate'] = $itemDev['estimate'];
            @$status['images'] = $itemDev['images'];
          } else {
            $status = $defaultDev;
          }

          $status['notes'] = '';
          if (file_exists($notesFile)) {
            $status['notes'] = file_get_contents($notesFile);
          }

          $item = array(
            'slug' => str_replace(".twig", "", $item),
            'url' => str_replace(".twig", "", $url),
            'designs' => $designs,
            'assigned' => $status['assigned'],
            'status' => $status['status'],
            'estimate' => $status['estimate'],
            'notes' => $status['notes'],
            'images' => $status['images'],
          );
        });

        return $plates;
    }

    function templateFolders()
    {
        return RunwayModule::getInstance()->template->getFolders();
    }
}
