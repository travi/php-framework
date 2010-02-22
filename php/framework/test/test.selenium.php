<?php

require_once(dirname(__FILE__) . '/../../thirdparty/simpletest/autorun.php');

class Example extends PHPUnit_Extensions_SeleniumTestCase
{
  function setUp()
  {
    $this->setBrowser("*firefox");
    $this->setBrowserUrl("http://travi.org/");
  }

  function testMyTestCase()
  {
    $this->open("/admin/wiki/index.php?title=TeamCity#Continuous_Integration");
    $this->click("link=PHP");
    $this->waitForPageToLoad("30000");
    $this->click("link=Continuous Integration");
    $this->waitForPageToLoad("30000");
  }
}
?>