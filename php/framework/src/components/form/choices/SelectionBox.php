<?php
/**
 * Created on Feb 13, 2011
 * By Matt Travi
 * programmer@travi.org
 */
require_once 'Choices.php';

class SelectionBox extends Choices
{
    private $optGroups = array();

    public function __construct($options=array())
    {
        $this->addOption("Select One");
        parent::__construct($options);
        $this->setTemplate('components/form/selectionBox.tpl');
    }
    protected function optionAdder($options=array())
    {
        if ($this->settings['optGroups']) {
            foreach ($options as $optGroup => $values) {
                $this->optGroups[$optGroup] = array();
                foreach ($values as $value) {
                    $selected=false;
                    $disabled=false;

                    $optionAR = array(  'option'    => $value['label'],
                                        'value'     => $value['value'],
                                        'selected'  => $selected,
                                        'disabled'  => $disabled);

                    array_push($this->optGroups[$optGroup], $optionAR);
                }
            }
        } else {
            parent::optionAdder($options);
        }
    }
}
 
