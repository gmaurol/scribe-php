<?php

class URLTest extends PHPUnit_Framework_TestCase {
  const TEST_URL = 'http://test.com';
  const TEST_QUERY = 'query=test&somekey=someval';

  private $subject;

  protected function setUp() {
    $this->subject = new URL(sprintf("%s?%s", self::TEST_URL, self::TEST_QUERY));
  }

  public function testGetQuery() {
    $this->assertEquals(self::TEST_QUERY, $this->subject->getQuery());
  }

  public function testOpenConnection() {
    $httClassName = Config::get('http_connection_classname');
    Config::set('http_connection_classname', 'DummyHttpUrlConn');
    $conn = $this->subject->openConnection();
    $this->assertTrue($conn instanceof DummyHttpUrlConn);
    $this->assertEquals(
      sprintf("%s?%s", self::TEST_URL, self::TEST_QUERY), $conn->getUrl()
    );

    Config::set('http_connection_classname', $httClassName);
  }

}

class DummyHttpUrlConn {

  private $url;

  public function __construct($u) {
    $this->url = $u;
  }

  public function getUrl() {
    return $this->url;
  }

}