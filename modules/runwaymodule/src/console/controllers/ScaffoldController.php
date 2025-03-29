<?php

namespace modules\runwaymodule\console\controllers;

use Craft;
use yii\console\Controller;
use yii\helpers\Console;

use craft\helpers\FileHelper;
use benf\neo\models\BlockType;

class ScaffoldController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Handle runway/scaffold console commands, optionally pass in a comma separated list of handles
     *
     */
    public function actionIndex(array $buildBlocks = [])
    {
        /**
         * We want to build out an array of block names and field layouts that can be used to scaffold twig templates
         *
         * _blocks = [
         *  {
         *    handle = 'pageHero',
         *    name = 'Page Hero',
         *    fieldLayoutId = 12,
         *    children = [],
         *  },
         *  {
         *    handle = 'longform',
         *    name = 'Longform',
         *    fieldLayoutId = 0,
         *    children = [
         *      {
         *        handle = 'body',
         *        name = 'Body',
         *        fieldLayoutId = 3,
         *      },
         *      {
         *        handle = 'textImage',
         *        name = 'Text/Image',
         *        fieldLayoutId = 4,
         *      }
         *    ],
         *  },
         * ]
         */

        $_blocks = [];

        // Look for a Blocks field type
        $blocks = Craft::$app->fields->getFieldByHandle('blocks');

        if(!$blocks) {
            $this->stdout("A field with the handle 'blocks' could not be found." . PHP_EOL, Console::FG_RED);
            return false;
        }

        // Get the structure from Neo
        if ($blocks->displayName() == 'Neo') {
            $this->stdout("1. Discovered a Neo field with the handle 'blocks'" . PHP_EOL, Console::FG_YELLOW);

            // Loop over top level Block Types
            /** @var $block BlockType */
            foreach ($blocks->getBlockTypes() as $block) {
                // Check it's top level
                if ($block->topLevel) {

                    $children = [];
                    if ($block->childBlocks) {
                        $children = $this->_getNeoChildBlocks($block->childBlocks);
                    }

                    $_blocks[] = [
                        'handle' => $block->handle,
                        'name' => $block->name,
                        'fieldLayoutId' => $block->fieldLayoutId,
                        'children' => $children
                    ];
                }
            }
        }

        // Get the structure from Matrix
        elseif ($blocks->displayName() == 'Matrix') {
            $this->stdout("1. Discovered a Matrix field with the handle 'blocks'" . PHP_EOL, Console::FG_YELLOW);

            foreach ($blocks->getBlockTypes() as $block) {
                $_blocks[] = [
                    'handle' => $block->handle,
                    'name' => $block->name,
                    'fieldLayoutId' => $block->fieldLayoutId,
                    'children' => []
                ];
            }
        }

        // Get root path for saving Blocks
        $blocksPath = '_blocks';

        // Optionally filter to blocks passed as arguments
        if (count($buildBlocks)) {
            $_blocks = array_filter($_blocks, fn($f) => in_array($f['handle'], $buildBlocks));
        }

        $this->stdout(
            sprintf('2. Found %d matching top level elements', count($_blocks)) . PHP_EOL,
            Console::FG_YELLOW
        );

        // Loop over each block type
        foreach($_blocks as $block) {
            $this->stdout(sprintf('- %s', $block['name']) . PHP_EOL, Console::FG_GREEN);

            $fileName = 'index.twig';
            $blockFolder = $blocksPath . DIRECTORY_SEPARATOR . $block['handle'];

            // Create folder if it doesn't exist
            if (!is_dir(Craft::$app->getPath()->getSiteTemplatesPath() . DIRECTORY_SEPARATOR . $blockFolder)) {
                FileHelper::createDirectory(Craft::$app->getPath()->getSiteTemplatesPath() . DIRECTORY_SEPARATOR . $blockFolder);
            }

            // Write the index file
            $filePath = $blockFolder . DIRECTORY_SEPARATOR . 'index.twig';

            // Prompt to overwrite
            if (
                !file_exists(Craft::$app->getPath()->getSiteTemplatesPath() . DIRECTORY_SEPARATOR . $filePath)
                || $this->confirm(sprintf("-- Overwrite '%s'?", $filePath), false)
            ) {
                $this->_writeTwigTemplate($filePath, $block['fieldLayoutId']);
            }

            // Check for child files
            foreach($block['children'] as $child) {

                $filePath = $blockFolder . DIRECTORY_SEPARATOR . $child['handle'] . '.twig';

                // Prompt to overwrite
                if (
                    !file_exists(Craft::$app->getPath()->getSiteTemplatesPath() . DIRECTORY_SEPARATOR . $filePath)
                    || $this->confirm(sprintf("-- Overwrite '%s'?", $filePath), false)
                ) {
                    $this->_writeTwigTemplate($filePath, $child['fieldLayoutId']);
                }
            }
        }

        return "Done.";
    }

    private function _getNeoChildBlocks(array $children, $output = []): array {

        foreach( $children as $child) {
            $childBlock = \benf\neo\Plugin::$plugin->blockTypes->getByHandle($child);

            if ($childBlock->childBlocks) {
                $output = $this->_getNeoChildBlocks($childBlock->childBlocks, $output);
            }
            else {
                $output[$child] = [
                    'handle' => $childBlock->handle,
                    'name' => $childBlock->name,
                    'fieldLayoutId' => $childBlock->fieldLayoutId,
                ];
            }
        }

        return $output;
    }

    private function _writeTwigTemplate($filePath, $fieldLayoutId): void {

        // Setup template
        $head = [];
        $body = [];

        // The fieldLayoutId points to all the tabs & fields for this block
        // if the layout is empty then it's probably a wrapper for child blocks
        if ($fieldLayoutId) {
            $fields = \Craft::$app->getFields()->getLayoutById($fieldLayoutId)->getCustomFields();

            $requiredFields = array_filter($fields, fn($f) => $f->required);
            $optionalFields = array_filter($fields, fn($f) => !$f->required);

            $body[] = '';
            $body[] = '<section>';
            $body[] = '  <div class="container">';
            $body[] = '';
            
            // Output required fields
            if (count($requiredFields)) {
                $head[] = '{# Required #}';
                foreach ($requiredFields as $field) {
                    $head[] = $this->_renderHead($field);

                    $body = array_merge($body, $this->_renderBody($field));
                }
            }

            if (count($requiredFields) && count($optionalFields)) {
                $head[] = '';
            }

            // Output optional fields
            if (count($optionalFields)) {
                $head[] = '{# Optional #}';

                foreach ($optionalFields as $field) {
                    $head[] = $this->_renderHead($field);

                    $body = array_merge($body, $this->_renderBody($field)
                    );
                }
            }

            $body[] = '  </div>';
            $body[] = '</section>';
        }
        else {
            $head[] = '{# Include child blocks #}';
            $head[] = '';

            $body[] = '{% for block in block.children.all() %}';
            $body[] = '  {% include _self ~ "/" ~ block.type %}';
            $body[] = '{% endfor %}';
        }

        // Write file and output
        $twig = implode("\n", $head);
        $twig .= "\n";
        $twig .= implode("\n", $body);
        $twig .= "\n";

        if (
            file_put_contents(
                Craft::$app->getPath()->getSiteTemplatesPath() . DIRECTORY_SEPARATOR . $filePath,
                $twig
            )
        ) {
            $this->stdout(sprintf('-> Written %s', $filePath) . PHP_EOL, Console::FG_YELLOW);
        }
    }

    private function _renderHead($field): string
    {
        $out = sprintf('{%% set %s = block.%s', $field->handle, $field->handle);

        if (!$field->required) {
            $out .= ' ?? ';
            switch ($field->displayName()) {
                case 'Assets':
                case 'Table':
                    $out .= '[]';
                    break;

                case 'Plain Text':
                case 'Redactor':
                    $out .= '\'\'';
                    break;

                default:
                    $out .= 'null';
            }
        }

        $out .= sprintf(' %%} {# %s #}', $field->displayName());

        return $out;
    }

    private function _renderBody($field): array {

        $out = [];

        $indent = ($field->required ? 4 : 6);
        switch ($field->displayName()) {
            case 'Assets':
                if ($field->maxRelations == 1) {
                    $out[] = "    {% if {$field->handle} %}";
                    $out[] = "      <div><img src=\"{{ {$field->handle}.one.url }}\" alt=\"{{ {$field->handle}.one.alt }}\"></div>";
                    $out[] = "    {% endif %}";
                }
                else {
                    $out[] = "    <div class=\"grid grid-cols-4 gap-4\">";
                    $out[] = "      {% for img in {$field->handle} %}";
                    $out[] = "        <div><img src=\"{{ img.url }}\" alt=\"{{ img.alt }}\"></div>";
                    $out[] = "      {% endfor %}";
                    $out[] = "    </div>";
                }
                break;

            case "Dropdown":
                $out[] = "    {% if {$field->handle} %}";
                $out[] = "      <div>{{ {$field->handle}.label }}</div>";
                $out[] = "    {% endif %}";
                break;

            case 'Entries':
                if ($field->maxRelations == 1) {
                    $out[] = "    {% if {$field->handle} %}";
                    $out[] = "      <div>{{ {$field->handle}.one.getLink()|attr({class: 'btn') }}</div>";
                    $out[] = "    {% endif %}";
                }
                else {
                    $out[] = "    <ul>";
                    $out[] = "      {% for e in {$field->handle}.all() %}";
                    $out[] = "        <li>{{ e.getLink() }}</li>";
                    $out[] = "      {% endfor %}";
                    $out[] = "    </ul>";
                }
                break;

            case 'Link field':
                $out[] = "    {% if {$field->handle} and not {$field->handle}.isEmpty %}";
                $out[] = "      <div>{{ {$field->handle}.getLink() }}</div>";
                $out[] = "    {% endif %}";
                break;

            case 'Table':
                $columns = count($field->columns);
                $out[] = "    {% if {$field->handle} %}";
                $out[] = "      <div class=\"grid grid-cols-{$columns} gap-4 \">";
                foreach ($field->columns as $col) {
                    $out[] = "          <div><strong>{$col['heading']}</strong></div>";
                }
                $out[] = "        {% for row in {$field->handle} %}";
                foreach ($field->columns as $col) {
                    $out[] = "          <div>{{ row.{$col['handle']} }}</div>";
                }
                $out[] = "        {% endfor %}";
                $out[] = "      </div>";
                $out[] = "    {% endif %}";
                break;

            default:
                $out[] = "    {% if {$field->handle} %}";
                $out[] = "      <div>{{ {$field->handle} }}</div>";
                $out[] = "    {% endif %}";
                break;
        }

        $out[] = '';

        return $out;
    }
}
