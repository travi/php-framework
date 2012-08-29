<?php

namespace Travi\framework\components\Forms;

use Travi\framework\components\Forms\FormElementGroup;

class FieldSet extends FormElementGroup
{
    /** @var string */
    private $legend;

    public function __construct($options = array())
    {
        $this->legend = $options['legend'];
        foreach ($options['fields'] as $field) {
            if (is_a($field, Form::FORMS_NAMESPACE . 'Field')) {
                $this->addFormElement($field);
            } else {
                $this->addFormElement(new $field['type']($field));
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