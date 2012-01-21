<?php

require_once 'Input.php';

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

        $this->setClass("textInput");
        $this->setType("text");
    }
}