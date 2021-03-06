<?php

namespace travi\framework\navigation;

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