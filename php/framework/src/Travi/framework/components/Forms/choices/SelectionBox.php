<?php

namespace travi\framework\components\Forms\choices;

use travi\framework\components\Forms\choices\Choices;

class SelectionBox extends Choices
{
    private $optGroups = array();

    public function __construct($options=array())
    {
        $this->addOption("Select One");
        parent::__construct($options);
        $this->setTemplate('components/form/selectionBox.tpl');
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    protected function optionAdder($options=array())
    {
        if (isset($this->settings['optGroups'])) {
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
 
