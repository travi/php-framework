<?php

namespace Travi\framework\controller;

use Travi\framework\controller\AbstractController,
    Travi\framework\http\Request,
    Travi\framework\http\Response;

class ErrorController extends AbstractController
{
    /**
     * @param  $request Request
     * @param  $response Response
     * @param  $error \Exception
     * @return void
     */
    public function error404(&$request, &$response, $filters, $error)
    {
        //TODO: also include link to contact page...
        $response->setStatus(404);
        $response->setTitle('Page Could Not Be Found');
        $response->setPageTemplate('../error/404.tpl');

        if (isset($error)) {
            //TODO: only show this in dev mode, but log it in other environments
            $response->addToResponse('errorMessage', $error->getMessage());
        }
    }

    /**
     * @param  $request Request
     * @param  $response Response
     * @return void
     */
    public function error401(&$request, &$response)
    {
        $response->setStatus(401);
        header('WWW-Authenticate: Basic realm="Travi Admin"');
        $response->setTitle('You are not authorized to view this page');
        $response->setPageTemplate('../error/401.tpl');
    }

    /**
     * @param  $request Request
     * @param  $response Response
     * @param $filters
     * @param  $error \Exception
     * @return void
     */
    public function error500(&$request, &$response, $filters, $error)
    {
        //TODO: also include link to contact page...
        $response->setStatus(500);
        $response->setTitle('Internal Server Error');
        $response->setPageTemplate('../error/500.tpl');

        //TODO: only show this in dev mode, but log it in other environments
        $response->addToResponse('type', get_class($error));
        $response->addToResponse('errorMessage', $error->getMessage());
        $response->addToResponse('trace', $error->getTrace());
    }
}
