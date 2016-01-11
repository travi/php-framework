<?php

use travi\framework\email\EmailAddress;
use travi\framework\email\Email;
use travi\framework\email\EmailSender;
use travi\framework\email\SendMailSender;

class SendMailSenderTest extends PHPUnit_Framework_TestCase {
    private $mailer;
    const TO = 'To';
    const SUBJECT = 'Subject';
    const MESSAGE = 'Message';
    const NAME = 'some name';
    const EMAIL = 'some email';
    private $from;

    /** @var  SendMailSender */
    private $sender;

    public function setUp()
    {
        $this->sender = new SendMailSenderShunt();

        $this->mailer = $this->getMock('SendMailerToMock');
        $this->sender->setMailer($this->mailer);

        $this->from = new EmailAddress();
        $this->from->setName(self::NAME);
        $this->from->setAddress(self::EMAIL);
    }

    public function testThatMailIsSentFromMultipleParams()
    {
        $this->mailer->expects($this->once())
            ->method('mail')
            ->with(self::TO, self::SUBJECT, self::MESSAGE, "From: " . self::NAME . " <" . self::EMAIL . ">");

        $this->sender->send(self::TO, $this->from, self::SUBJECT, self::MESSAGE);
    }

    public function testThatMailIsSentFromEmailObject()
    {
        $this->mailer->expects($this->once())
            ->method('mail')
            ->with(self::TO, self::SUBJECT, self::MESSAGE, "From: " . self::NAME . " <" . self::EMAIL . ">");

        $email = new Email();
        $to = new EmailAddress();
        $to->setAddress(self::TO);
        $email->setTo($to);
        $email->setSubject(self::SUBJECT);
        $email->setMessage(self::MESSAGE);
        $email->setFrom($this->from);

        $this->sender->sendEmail($email);
    }
}

class SendMailSenderShunt extends SendMailSender
{
    /** @var  SendMailerToMock */
    private $mailer;

    protected function mail($to, $from, $subject, $message)
    {
        $this->mailer->mail($to, $subject, $message, $from);
    }

    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }
}

class SendMailerToMock
{
    public function mail($to, $subject, $message, $headers)
    {

    }
}