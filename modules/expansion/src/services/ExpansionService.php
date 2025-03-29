<?php
/**
 * Expansion module for Craft CMS 3.x
 *
 * A utility module for business logic and additional functionality.
 *
 * @link      https://simplygoodwork.com
 * @copyright Copyright (c) 2022 Good Work
 */

namespace modules\expansion\services;

use modules\expansion\Expansion;

use Craft;
use craft\base\Component;
use yii\log\Logger;

/**
 * @author    Good Work
 * @package   Expansion
 * @since     1.0.0
 */
class ExpansionService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
//        Craft::dd('whats hapening?'); // dump & die for debugging purposes
//        Craft::info('I\'m Pickle Riccckkkkk!', __METHOD__); // log an Info message
//        Craft::warning('Someone help me I\'m out of good quote ideas.', __METHOD__); // log a Warning message
//        Craft::error('Shut down all the trash compactors on the detention level!', __METHOD__); // log an error
        return $result;
    }
}
