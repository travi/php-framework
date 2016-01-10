<?php

namespace travi\framework\email;

abstract class EmailSender
{
    /**
     * @param $to
     * @param $from EmailAddress
     * @param $subject
     * @param $content
     */
    public function send($to, $from, $subject, $content)
    {
        $this->mail($to, $subject, $content, $this->formatFromHeader($from));
    }

    /**
     * @param $email Email
     */
    public function sendEmail($email)
    {
        $this->mail(
            $email->getTo()->getAddress(),
            $email->getSubject(),
            $email->getMessage(),
            $this->formatFromHeader($email->getFrom())
        );
    }

    protected abstract function mail($to, $subject, $message, $headers);

    /**
     * @param $from
     * @return string
     */
    private function formatFromHeader($from)
    {
        return "From: " . $from->getName() . " <" . $from->getAddress() . ">";
    }
}