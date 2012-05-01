<?php
require_once dirname(__FILE__).'/../../../src/exception/NavigationTypeNotAnOption.exception.php';

class NavigationFactory
{
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
            return new $navType;
        } else {
            throw new NavigationTypeNotAnOption($type . ' is not a valid navigation type');
        }
    }
}
