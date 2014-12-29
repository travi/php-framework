<?php

namespace travi\framework\email;


class Email
{
    /** @var  EmailAddress */
    private $to;
    /** @var  EmailAddress */
    private $from;
    /** @var  string */
    private $subject;
    /** @var  string */
    private $message;

    /**
     * @return EmailAddress
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return EmailAddress
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}