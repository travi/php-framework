<?php

namespace travi\framework\email;

use travi\framework\exception\EmailNotAcceptedForDeliveryException;

class SendMailSender extends EmailSender
{
    protected function mail($to, $subject, $message, $headers)
    {
        if (false === mail($to, $subject, $message, $headers)) {
            throw new EmailNotAcceptedForDeliveryException();
        }
    }
}