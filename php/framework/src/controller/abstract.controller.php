<?php
/**
 * User: travi
 * Date: Jan 1, 2011
 * Time: 5:43:55 PM
 */
 
abstract class AbstractController {
    abstract public function doAction(&$request, &$response);
}
