<?php

class MissingLocalPathToResourceException extends Exception
{
    function __construct($fileName)
    {
        parent::__construct('Local URI required for ' . $fileName);
    }

}
