<?php

namespace Travi\framework\components\Forms;

use Travi\framework\content\ContentObject;

class NoteArea extends ContentObject
{
    private $label;
    private $content;

    public function __construct($options)
    {
        $this->label = $options['label'];
        $this->content = $options['content'];
        $this->setTemplate('components/form/noteArea.tpl');
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getValidations()
    {
        return array();
    }
}