<?php

require_once dirname(__FILE__).'/FormElementGroup.php';

class FieldSet extends FormElementGroup
{
    /** @var string */
    private $legend;

    public function __construct($options = array())
    {
        $this->legend = $options['legend'];
        foreach ($options['fields'] as $field) {
            if (is_a($field, 'Field')) {
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