<?php

namespace Travi\framework\components\Forms\inputs;

use Travi\framework\components\Forms\inputs\Input;

class DateInput extends Input
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setType("date");
        $this->setClass("textInput datepicker");
        $this->addJavaScript('jqueryUi');
        $this->addJsInit(
            "$('input.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                buttonImage: '/resources/shared/img/calendar.gif',
                buttonImageOnly: true, showOn: 'both'
            });"
        );
    }
}
