<?php

namespace travi\framework\components\Forms\choices;

class SelectionBox extends Choices
{
    private $optGroups = array();

    public function __construct($options=array())
    {
        $this->addOption("Select One", '');
        parent::__construct($options);
        $this->setTemplate('components/form/selectionBox.tpl');
    }

    protected function optionAdder($options=array())
    {
        if (isset($this->settings['optGroups'])) {
            foreach ($options as $optGroup => $values) {
                $this->optGroups[$optGroup] = array();
                foreach ($values as $value) {
                    $optionAR = array(
                        'option' => $value['label'],
                        'value' => $value['value'],
                        'selected' => false,
                        'disabled' => false
                    );

                    array_push($this->optGroups[$optGroup], $optionAR);
                }
            }
        } else {
            parent::optionAdder($options);
        }
    }
}
 
