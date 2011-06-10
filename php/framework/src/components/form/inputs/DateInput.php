<?php
/**
 * Created on Jan 26, 2011
 * By Matt Travi
 * programmer@travi.org
 */

require_once('Input.php');


class DateInput extends Input
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setType("text");
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
