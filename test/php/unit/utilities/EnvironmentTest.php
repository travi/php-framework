<?php

use travi\framework\utilities\Environment;

class EnvironmentTest extends PHPUnit_Framework_TestCase
{
    const PROD_URL = 'prodUrl';
    /** @var  Environment */
    private $environment;

    public function setUp()
    {
        $this->environment = new Environment();
        $this->environment->setProductionUrl(self::PROD_URL);
    }

    public function testReportedAsLocalWhenContainsLocalTopLevelDomain()
    {
        $_SERVER['HTTP_HOST'] = 'currentUrl.dev';

        $this->assertTrue($this->environment->isLocal());
        $this->assertFalse($this->environment->isProduction());
    }

    public function testReportedAsNeitherLocalNorProdWhenNoMatch()
    {
        $_SERVER['HTTP_HOST'] = 'currentUrl';

        $this->assertFalse($this->environment->isLocal());
        $this->assertFalse($this->environment->isProduction());
    }

    public function testReportedAsProdWhenContainsProdUrl()
    {
        $_SERVER['HTTP_HOST'] = self::PROD_URL;

        $this->assertFalse($this->environment->isLocal());
        $this->assertTrue($this->environment->isProduction());
    }
}
