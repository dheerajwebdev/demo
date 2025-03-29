<?php
/**
 * Expansion module for Craft CMS 3.x
 *
 * A utility module for business logic and additional functionality.
 *
 * @link      https://simplygoodwork.com
 * @copyright Copyright (c) 2022 Good Work
 */

namespace modules\expansion\controllers;

use modules\expansion\Expansion;

use Craft;
use craft\web\Controller;

/**
 * @author    Good Work
 * @package   Expansion
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    int|bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'.
     *         If there's a public method below that is not added to the $allowAnonymous attribute,
     *             the endpoint can still be reached by users that are logged in.
     * @access protected
     */
    protected int|bool|array $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

//    public function actionIndex()
//    {
//        $result = 'Welcome to the DefaultController actionIndex() method';
//        Expansion::$instance->expansionService->exampleService();
//        return $result;
//    }

//    public function actionDoSomething()
//    {
//        $result = 'Welcome to the DefaultController actionDoSomething() method';
//
//        return $result;
//    }

//    public function actionCheatSheet()
//    {
//        $this->requireLogin(); // require requester to be logged in
//        $this->requireAdmin(); // require requester to be an admin
//        $this->requirePostRequest();
//        $this->request->getQueryParam('q'); // get value of 'q' query param
//        $this->request->getRequiredParam('q'); // throws an error if 'q' query param is not provided in request
//        $this->request->getBodyParam('data'); // get body param, e.g. if making a POST request
//        $this->asJson(['key' => 'value']); // return JSON data
//        $this->asRaw('<h1>hello world</h1>'); // return raw data, e.g. html
//        $this->redirect($url); // redirect to supplied URL
//        $this->renderTemplate('template/path'. $data); // render template with supplied data
//        $exampleServiceResult = Expansion::$instance->expansionService->exampleService(); // call Expansion service method
//    }
}
