<?php
/**
 * Expansion module for Craft CMS 3.x
 *
 * A utility module for business logic and additional functionality.
 *
 * @link      https://simplygoodwork.com
 * @copyright Copyright (c) 2022 Good Work
 */

namespace modules\expansion\variables;

use modules\expansion\Expansion;

use Craft;

/**
 * @author    Good Work
 * @package   Expansion
 * @since     1.0.0
 */
class ExpansionVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param null $optional
     * @return string
     *  call this in the templates via {{ craft.expansion.exampleVariable() }} or {{ craft.expansion.exampleVariable }}
     */
    public function exampleVariable($optional = null)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }
}
