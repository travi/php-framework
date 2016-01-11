<?php

namespace travi\framework\email;

interface EmailSender
{
    /**
     * @param $to
     * @param $from EmailAddress
     * @param $subject
     * @param $content
     */
    public function send($to, $from, $subject, $content);

    /**
     * @param $email Email
     */
    public function sendEmail($email);
}