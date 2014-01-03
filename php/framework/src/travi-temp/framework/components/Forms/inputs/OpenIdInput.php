<?php

namespace travi\framework\components\Forms\inputs;

use travi\framework\components\Forms\inputs\Input;

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