<?php

namespace Travi\framework\content\navigation;

use Travi\framework\content\navigation\NavigationObject;

class MenuBar extends NavigationObject
{
    public function __construct()
    {
        $this->addJavaScript('jqueryUi');
        $this->addJavaScript('/resources/shared/js/plugins/jquery.menubar.js');
        $this->addStyleSheet('/resources/shared/css/ui/menuBar.css');
        $this->addJsInit("$('ul.menuBar').menuBar();");
        $this->setTemplate('components/menuBar.tpl');
    }
}
