<?php

namespace travi\framework\email;

use travi\framework\exception\EmailNotAcceptedForDeliveryException;

class SendMailSender implements EmailSender
{
    /**
     * @param $to
     * @param $from EmailAddress
     * @param $subject
     * @param $content
     */
    public function send($to, $from, $subject, $content)
    {
        $this->mail($to, $this->formatFromHeader($from), $subject, $content);
    }

    /**
     * @param $email Email
     */
    public function sendEmail($email)
    {
        $this->mail(
            $email->getTo()->getAddress(),
            $this->formatFromHeader($email->getFrom()),
            $email->getSubject(),
            $email->getMessage()
        );
    }

    protected function mail($to, $from, $subject, $message)
    {
        if (false === mail($to, $subject, $message, $this->formatFromHeader($from))) {
            throw new EmailNotAcceptedForDeliveryException();
        }
    }

    /**
     * @param $from EmailAddress
     * @return string
     */
    private function formatFromHeader($from)
    {
        return "From: " . $from->getName() . " <" . $from->getAddress() . ">";
    }
}