<?php
require_once dirname(__FILE__).'/abstract.controller.php';
 
class ErrorController extends AbstractController
{
    /**
     * @param  $request Request
     * @param  $response Response
     * @param  $error Exception
     * @return void
     */
    public function error404(&$request, &$response, $error)
    {
        //TODO: also include link to contact page...
        header('HTTP/1.1 404 Not Found');
        $response->setTitle('Page Could Not Be Found');
        $response->setPageTemplate('../error/404.tpl');
        //TODO: only show this in dev mode, but log it in other environments
        $response->addToResponse('errorMessage', $error->getMessage());
    }

    /**
     * @param  $request Request
     * @param  $response Response
     * @return void
     */
    public function error401(&$request, &$response)
    {
        header('HTTP/1.0 401 Unauthorized');
        $response->setTitle('You are not authorized to view this page');
        $response->setPageTemplate('../error/401.tpl');
    }

    /**
     * @param  $request Request
     * @param  $response Response
     * @param  $error Exception
     * @return void
     */
    public function error500(&$request, &$response, $error)
    {
        //TODO: also include link to contact page...
        header('HTTP/1.1 500 Internal Server Error');
        $response->setTitle('Internal Server Error');
        $response->setPageTemplate('../error/500.tpl');
        //TODO: only show this in dev mode, but log it in other environments
        $response->addToResponse('message', $error->getMessage());
        $response->addToResponse('trace', $error->getTrace());
    }
}
