<?php

namespace travi\framework\content\navigation;

use travi\framework\content\navigation\NavigationObject;

class Tabs extends NavigationObject
{
    function __construct()
    {
        $this->addJavaScript('jqueryUi');
        $this->addJsInit(
            "$('.ui-tabs').tabs({
                selected: 0, fx: {opacity: 'toggle', height: 'toggle'}
            });"
        );
        $this->setTemplate('components/tabs.tpl');
    }
}