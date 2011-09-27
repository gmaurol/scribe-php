<?php

class RequestTest extends PHPUnit_Framework_TestCase {

  private $subject;
  const TEST_URL = 'http://localhost/';
  const TEST_QUERY = '?q=1&t=2';
  const TEST_VERB = 'GET';
  const HEADER_NAME = 'X-Test-Header-Name';
  const HEADER_VALUE = 'Header-Value';
  const PARAM_NAME = 'param';
  const PARAM_VALUE = 'value';
  const PARAM2_NAME = 'param2';
  const PARAM2_VALUE = 'value2';
  const TEST_BODY = 'TEST_BODY';
  const TEST_CHARSET = 'UTF-8';

  protected function setUp() {
    Config::set('http_connection_classname', 'HttpMock');
    $this->subject = new Request(self::TEST_VERB, self::TEST_URL . self::TEST_QUERY);
  }

  protected function tearDown() {
    $this->subject = null;
  }

  public function testAddHeaders() {
    $connection = $this->getMock(
      'HttpURLConnectionCurl', array('setRequestProperty'), array(self::TEST_URL . self::TEST_QUERY)
    );
    $connection->expects($this->once())
      ->method('setRequestProperty')
      ->with(self::HEADER_NAME, self::HEADER_VALUE);

    $this->subject->addHeader(self::HEADER_NAME, self::HEADER_VALUE);
    $this->subject->addHeaders($connection);
  }

  public function testAddBody() {
    $connection = $this->getMock(
      'HttpURLConnectionCurl', array('setRequestProperty', 'setDoOutput', 'write'), array(self::TEST_URL)
    );
    $connection->expects($this->at(0))
      ->method('setRequestProperty')
      ->with(Request::CONTENT_LENGTH, strlen(self::TEST_BODY));
    $connection->expects($this->at(1))
      ->method('setDoOutput')
      ->with(true);
    $connection->expects($this->at(2))
      ->method('write')
      ->with(self::TEST_BODY);
    $this->subject->addBody($connection, self::TEST_BODY);
  }

  public function testAddHeader() {
    $this->subject->addHeader(self::HEADER_NAME, self::HEADER_VALUE);
    $this->assertEquals(
      array(self::HEADER_NAME => self::HEADER_VALUE), $this->subject->getHeaders());
  }

  public function testAddBodyParameter() {
    $this->subject->addBodyParameter(self::PARAM_NAME, self::PARAM_VALUE);
    $this->assertEquals(
      array(self::PARAM_NAME => self::PARAM_VALUE), $this->subject->getBodyParams());
  }

  /* public function testAddQuerystringParameter() {
    $this->subject->addQuerystringParameter(self::PARAM_NAME, self::PARAM_VALUE);
    // TODO: test this
    } */

  public function testGetUrl() {
    $this->assertEquals(self::TEST_URL . self::TEST_QUERY, $this->subject->getUrl());
  }

  public function testGetSanitizedUrl() {
    $this->assertEquals(self::TEST_URL, $this->subject->getSanitizedUrl());
  }

  public function testAddPayload() {
    $this->subject->addPayload(self::TEST_BODY);
    $this->assertEquals(self::TEST_BODY, $this->subject->getBodyContents());
  }

  public function testGetBodyContents() {
    $this->subject->addBodyParameter(self::PARAM_NAME, self::PARAM_VALUE);
    $this->subject->addBodyParameter(self::PARAM2_NAME, self::PARAM2_VALUE);
    $q = self::PARAM_NAME . '=' . self::PARAM_VALUE . '&'
      . self::PARAM2_NAME . '=' . self::PARAM2_VALUE;

    $this->assertEquals($q, $this->subject->getBodyContents());
  }

  public function testGerVerb() {
    $this->assertEquals(self::TEST_VERB, $this->subject->getVerb());
  }

  public function testGetCharset() {
    $this->subject->setCharset(self::TEST_CHARSET);
    $this->assertEquals(self::TEST_CHARSET, $this->subject->getCharset());
  }

  public function testToString() {
    $this->assertEquals(
      sprintf(
        "@Request(%s %s)", self::TEST_VERB, self::TEST_URL . self::TEST_QUERY
      ), (string) $this->subject
    );
  }

}
