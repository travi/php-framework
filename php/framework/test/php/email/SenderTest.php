<?php

use travi\framework\email\Address;
use travi\framework\email\Sender;

class SenderTest extends PHPUnit_Framework_TestCase {
    private $mailer;

    public function testThatMailIsSent()
    {
        $to = 'To';
        $subject = 'Subject';
        $message = 'Message';
        $name = 'some name';
        $address = 'some email';

        $sender = new SenderShunt();
        $this->mailer = $this->getMock('MailerToMock');
        $sender->setMailer($this->mailer);

        $this->mailer->expects($this->once())
            ->method('mail')
            ->with($to, $subject, $message, "From: $name <$address>");

        $from = new Address();
        $from->setName($name);
        $from->setAddress($address);
        $sender->send($to, $from, $subject, $message);
    }
}

class SenderShunt extends Sender
{
    /** @var  MailerToMock */
    private $mailer;

    protected function mail($to, $subject, $message, $headers)
    {
        $this->mailer->mail($to, $subject, $message, $headers);
    }

    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }
}

class MailerToMock
{
    public function mail($to, $subject, $message, $headers)
    {

    }
}