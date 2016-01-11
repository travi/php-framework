<?php

namespace travi\framework\email;


class SendGridMapper
{

    /**
     * @param $to
     * @param $from EmailAddress
     * @param $subject
     * @param $message
     * @return \SendGrid\Email
     */
    public function mapParameters($to, $from, $subject, $message)
    {
        $email = new \SendGrid\Email();
        $email->addTo($to)
            ->setFrom($from->getAddress())
            ->setFromName($from->getName())
            ->setSubject($subject)
            ->setText($message);

        return $email;
    }

    /**
     * @param $email Email
     * @return \SendGrid\Email
     */
    public function mapEmail($email)
    {
        $sendGridEmail = new \SendGrid\Email();
        $sendGridEmail->addTo($email->getTo()->getAddress())
            ->setFrom($email->getFrom()->getAddress())
            ->setFromName($email->getFrom()->getName())
            ->setSubject($email->getSubject())
            ->setText($email->getMessage());

        return $sendGridEmail;
    }
}