<?php

namespace travi\framework\content\navigation;

use travi\framework\content\navigation\NavigationObject;

class Accordion extends NavigationObject
{
    public function __construct()
    {
        $this->addJavaScript('jqueryUi');
        $this->addJsInit("$('.accordion').accordion();");
        $this->setTemplate('components/accordion.tpl');
    }
}
