<?php

namespace Travi\framework\content\navigation;

use Travi\framework\exception\NavigationTypeNotAnOptionException;

class NavigationFactory
{
    const NAVIGATION_NAMESPACE = "\\Travi\\framework\\content\\navigation\\";

    const MENU_BAR = 'menuBar';
    const TABS = 'tabs';
    const ACCORDION = 'accordion';

    private $navOptions = array(
        self::ACCORDION => 'Accordion',
        self::TABS => 'Tabs',
        self::MENU_BAR => 'MenuBar'
    );

    public function build($type)
    {
        $navType = $this->navOptions[$type];
        if (!empty($navType)) {
            $type = self::NAVIGATION_NAMESPACE . $navType;
            return new $type;
        } else {
            throw new NavigationTypeNotAnOptionException($type . ' is not a valid navigation type');
        }
    }
}
