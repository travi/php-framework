<?php

namespace travi\framework\email;

class Sender
{
    /**
     * @param $to
     * @param $from Address
     * @param $subject
     * @param $content
     */
    public function send($to, $from, $subject, $content)
    {
        $this->mail($to, $subject, $content, "From: " . $from->getName() . " <" . $from->getAddress() . ">");
    }

    protected function mail($to, $subject, $message, $headers)
    {
        mail($to, $subject, $message, $headers);

    }
}