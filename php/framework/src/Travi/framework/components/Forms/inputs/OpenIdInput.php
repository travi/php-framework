<?php

namespace Travi\framework\components\Forms\inputs;

use Travi\framework\components\Forms\inputs\Input;

class OpenIdInput extends Input
{
    public function __construct()
    {
        parent::__construct(
            array(
                'label' => 'OpenID',
                'name'  => 'openid_identifier'
            )
        );

        $this->setClass("textInput open-id");
        $this->setType("text");
    }
}