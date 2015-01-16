<?php

namespace travi\framework\components\Forms\inputs;

class OpenIdInput extends Input
{
    public function __construct($options = array())
    {
        parent::__construct(
            array_merge(
                array(
                    'label' => 'OpenID',
                    'name'  => 'openid_identifier'
                ),
                $options
            )
        );

        $this->setClass("textInput open-id");
        $this->setType("text");
    }
}