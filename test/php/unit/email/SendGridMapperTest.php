<?php

use travi\framework\email\Email;
use travi\framework\email\EmailAddress;
use travi\framework\email\SendGridMapper;

class SendGridMapperTest extends PHPUnit_Framework_TestCase {
    const TO = 'To';
    const SUBJECT = 'Subject';
    const MESSAGE = 'Message';
    const NAME = 'some name';
    const EMAIL = 'some email';

    /** @var  EmailAddress */
    private $from;

    /** @var  SendGridMapper */
    private $mapper;

    public function setUp()
    {
        $this->mapper = new SendGridMapper();

        $this->from = new EmailAddress();
        $this->from->setName(self::NAME);
        $this->from->setAddress(self::EMAIL);
    }

    public function testThatParametersMappedToSendGridEmail()
    {
        $sendGridEmail = $this->mapper->mapParameters(self::TO, $this->from, self::SUBJECT, self::MESSAGE);

        $this->assertThatSendGridEmailPopulatedProperly($sendGridEmail);
    }

    public function testThatEmailMappedToSendGridEmail()
    {
        $email = new Email();
        $to = new EmailAddress();
        $to->setAddress(self::TO);
        $email->setTo($to);
        $email->setSubject(self::SUBJECT);
        $email->setMessage(self::MESSAGE);
        $email->setFrom($this->from);

        $sendGridEmail = $this->mapper->mapEmail($email);

        $this->assertThatSendGridEmailPopulatedProperly($sendGridEmail);
    }

    /**
     * @param $sendGridEmail
     */
    private function assertThatSendGridEmailPopulatedProperly($sendGridEmail)
    {
        $this->assertEquals(self::TO, $sendGridEmail->to[0]);
        $this->assertEquals($this->from->getAddress(), $sendGridEmail->from);
        $this->assertEquals($this->from->getName(), $sendGridEmail->fromName);
        $this->assertEquals(self::SUBJECT, $sendGridEmail->subject);
        $this->assertEquals(self::MESSAGE, $sendGridEmail->text);
    }
}