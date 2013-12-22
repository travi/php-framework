<?php

namespace Travi\framework\content\navigation;

use Travi\framework\content\navigation\NavigationObject;

class MenuBar extends NavigationObject
{
    public function __construct()
    {
        $this->addJavaScript('jqueryUi');
        $this->addJavaScript('/resources/thirdparty/travi-menubar/js/plugins/jquery.menubar.js');
        $this->addStyleSheet('/resources/thirdparty/travi-menubar/css/ui/menuBar.css');
        $this->addJsInit("$('ul.menuBar').menuBar();");
        $this->setTemplate('components/menuBar.tpl');
    }
}
