<?php

namespace travi\framework\utilities;

class Environment
{
    private $productionUrl;
    const LOCAL_KEY = '.dev';

    public function isProduction()
    {
        return (strpos($_SERVER["HTTP_HOST"], $this->productionUrl) !== false);
    }

    public function isLocal()
    {
        return (strpos($_SERVER["HTTP_HOST"], self::LOCAL_KEY) !== false);
    }

    public function setProductionUrl($productionUrl)
    {
        $this->productionUrl = $productionUrl;
    }
}
