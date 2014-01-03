<?php

namespace travi\framework\components\Forms\inputs;

use travi\framework\components\Forms\inputs\Input;

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
                buttonImage: '/resources/thirdparty/travi-styles/img/calendar.gif',
                buttonImageOnly: true, showOn: 'both'
            });"
        );
    }
}
