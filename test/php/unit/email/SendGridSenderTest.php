<?php

use travi\framework\email\EmailAddress;
use travi\framework\email\Email;
use travi\framework\email\SendGridMapper;
use travi\framework\email\SendGridSender;


class SendGridSenderTest extends PHPUnit_Framework_TestCase {
    private $sendgrid;
    private $email;
    /** @var  SendGridMapper */
    private $mapper;
    const TO = 'To';
    const SUBJECT = 'Subject';
    const MESSAGE = 'Message';
    const NAME = 'some name';
    const EMAIL = 'some email';
    private $from;

    /** @var  SendGridSender */
    private $sender;

    public function setUp()
    {
        $this->sender = new SendGridSender();
        $this->email = new \SendGrid\Email();

        $this->mapper = $this->getMock('\\travi\\framework\\email\\SendGridMapper');
        $this->sender->setMapper($this->mapper);

        $this->sendgrid = $this->getMock('SendGrid');
        $this->sender->setSendGrid($this->sendgrid);

        $this->from = new EmailAddress();
        $this->from->setName(self::NAME);
        $this->from->setAddress(self::EMAIL);
    }

    public function testThatMailIsSentFromMultipleParams()
    {
        $this->mapper->expects($this->once())
            ->method('mapParameters')
            ->with(self::TO, $this->from, self::SUBJECT, self::MESSAGE)
            ->will($this->returnValue($this->email));
        $this->sendgrid->expects($this->once())
            ->method('send')
            ->with($this->email);

        $this->sender->send(self::TO, $this->from, self::SUBJECT, self::MESSAGE);
    }

    public function testThatMailIsSentFromEmailObject()
    {
//        $this->mailer->expects($this->once())
//            ->method('mail')
//            ->with(self::TO, self::SUBJECT, self::MESSAGE, "From: " . self::NAME . " <" . self::EMAIL . ">");

        $email = new Email();
        $to = new EmailAddress();
        $to->setAddress(self::TO);
        $email->setTo($to);
        $email->setSubject(self::SUBJECT);
        $email->setMessage(self::MESSAGE);
        $email->setFrom($this->from);

        $this->mapper->expects($this->once())
            ->method('mapEmail')
            ->with($email)
            ->will($this->returnValue($this->email));
        $this->sendgrid->expects($this->once())
            ->method('send')
            ->with($this->email);

        $this->sender->sendEmail($email);
    }
}