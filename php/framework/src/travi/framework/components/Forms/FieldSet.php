<?php

namespace travi\framework\components\Forms;

use travi\framework\components\Forms\FormElementGroup;

class FieldSet extends FormElementGroup
{
    /** @var string */
    private $legend;

    public function __construct($options = array())
    {
        if (isset($options['legend'])) {
            $this->legend = $options['legend'];
        }
        if (isset($options['fields'])) {
            foreach ($options['fields'] as $field) {
                if (is_a($field, Form::FORMS_NAMESPACE . 'Field')) {
                    $this->addFormElement($field);
                } else {
                    $this->addFormElement(new $field['type']($field));
                }
            }
        }
    }

    public function getLegend()
    {
        return $this->legend;
    }

    public function setLegend($legend)
    {
        $this->legend = $legend;
    }
}