<?php
/**
 * Created on Jan 15, 2011
 * By Matt Travi
 * programmer@travi.org
 */
require_once(dirname(__FILE__).'/abstract.controller.php');
 
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
        $response->addToResponse('errorMessage', $error->getMessage());  //TODO: only show this in dev mode, but log it in other environments
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
        $response->addToResponse('errorMessage', $error->getMessage());  //TODO: only show this in dev mode, but log it in other environments
    }
}
