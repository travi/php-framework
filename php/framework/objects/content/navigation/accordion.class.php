<?php

require_once 'navigation.class.php';
 
class Accordion extends NavigationObject
{
    public function __construct()
    {
        $this->addJavaScript('jqueryUi');
        $this->addJsInit("$('.accordion').accordion();");
        $this->setTemplate('components/accordion.tpl');
    }
}
