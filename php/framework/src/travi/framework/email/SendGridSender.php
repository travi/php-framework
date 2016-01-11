<?php

namespace travi\framework\email;

use travi\framework\exception\EmailNotAcceptedForDeliveryException;

class SendGridSender implements  EmailSender
{
    /** @var  SendGridMapper */
    private $mapper;
    /** @var  \SendGrid */
    private $sendgrid;

    /**
     * @param $to
     * @param $from EmailAddress
     * @param $subject
     * @param $content
     */
    public function send($to, $from, $subject, $content)
    {
        $this->sendgrid->send($this->mapper->mapParameters($to, $from, $subject, $content));
    }

    /**
     * @param $email Email
     */
    public function sendEmail($email)
    {
        $this->sendgrid->send($this->mapper->mapEmail($email));
    }

    /**
     * @PdInject new:travi\framework\email\SendGridMapper
     * @param $mapper
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @PdInject SendGridInstance
     * @param sendgrid
     */
    public function setSendGrid($sendgrid)
    {
        $this->sendgrid = $sendgrid;
    }
}