<?php

class OAuthConfigTest extends PHPUnit_Framework_TestCase {
  const API_KEY = 'api-key';
  const API_SECRET = 'api-secret';
  const CALLBACK = 'callback';
  const SIGNATURE_TYPE = 'header';
  const SCOPE = 'scope';

  private $subject;

  protected function setUp() {
    $this->subject = new OAuthConfig(
        self::API_KEY,
        self::API_SECRET,
        self::CALLBACK,
        self::SIGNATURE_TYPE,
        self::SCOPE
    );
  }

  public function testGetApiKey() {
    $this->assertEquals(self::API_KEY, $this->subject->getApiKey());
  }

  public function testGetApiSecret() {
    $this->assertEquals(self::API_SECRET, $this->subject->getApiSecret());
  }

  public function testGetCallback() {
    $this->assertEquals(self::CALLBACK, $this->subject->getCallback());
  }

  public function testGetSignatureType() {
    $this->assertEquals(
      self::SIGNATURE_TYPE, 
      $this->subject->getSignatureType()
    );
  }

  public function testGetScope() {
    $this->assertEquals(self::SCOPE, $this->subject->getScope());
  }

  public function testHasScope() {
    $this->assertTrue($this->subject->hasScope());
  }

}