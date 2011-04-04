<?php

require_once dirname(__FILE__).'/FormElementGroup.php';

class FieldSet extends FormElementGroup
{
    /** @var string */
    private $legend;

    public function __construct($options)
    {
        $this->legend = $options['legend'];
        foreach ($options['fields'] as $field) {
            $this->addFormElement(new $field['type']($field));
        }
    }

    public function getLegend()
    {
        return $this->legend;
    }
}