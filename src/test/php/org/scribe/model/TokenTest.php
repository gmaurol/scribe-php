<?php

class TokenTest extends PHPUnit_Framework_TestCase {
  const TOKEN = 'TOKEN';
  const SECRET = 'SECRET';
  const RESPONSE = 'RESPONSE';
  private $subject;

  protected function setUp() {
    $this->subject = new Token(
        self::TOKEN, self::SECRET, self::RESPONSE
    );
  }

  protected function setUpWithoutResponse() {
    $this->subject = new Token(
        self::TOKEN, self::SECRET, null
    );
  }

  public function testGetToken() {
    $this->assertEquals(self::TOKEN, $this->subject->getToken());
  }

  public function testGetSecret() {
    $this->assertEquals(self::SECRET, $this->subject->getSecret());
  }

  public function testGetRawResponse() {
    $this->assertEquals(self::RESPONSE, $this->subject->getRawResponse());
  }

  /**
   * @expectedException Exception
   */
  public function testGetRawResponseException() {
    $this->setUpWithoutResponse();
    $this->subject->getRawResponse();
  }

}
