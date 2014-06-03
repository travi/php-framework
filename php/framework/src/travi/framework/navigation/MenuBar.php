<?php

namespace travi\framework\navigation;

class MenuBar extends NavigationObject
{
    public function __construct()
    {
        $this->addJavaScript('jqueryUi');
        $this->addJavaScript('/resources/thirdparty/travi-menubar/js/jquery.menubar.js');
        $this->addStyleSheet('/resources/thirdparty/travi-menubar/css/ui/menuBar.css');
        $this->addJsInit("$('ul.menuBar').menuBar();");
        $this->setTemplate('components/menuBar.tpl');
    }
}
