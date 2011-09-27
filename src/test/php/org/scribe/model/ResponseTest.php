<?php

class ResponseTest extends PHPUnit_Framework_TestCase {
  const TEST_URL = "http://www.google.com.ar";
  private $conn;

  protected function setUp() {
    $this->conn = $this->getMock(
      'HttpURLConnectionCurl', array(
      'connect',
      'getResponseCode',
      'getHeaderFields',
      'getResponseBody',
      'getErrors'
      ), array(self::TEST_URL)
    );
  }

  public function testConstruct() {
    $this->conn->expects($this->once())
      ->method('connect');
    $this->conn->expects($this->once())
      ->method('getResponseCode')
      ->will($this->returnValue('200'));
    $this->conn->expects($this->once())
      ->method('getHeaderFields')
      ->will($this->returnValue(array('Content-type' => 'application/json')));
    $this->conn->expects($this->once())
      ->method('getResponseBody')
      ->will($this->returnValue('BODY'));

    $response = new Response($this->conn);
    $this->assertEquals('BODY', $response->getBody());
    $this->assertEquals(array('Content-type' => 'application/json'), $response->getHeaders());
    $this->assertEquals('application/json', $response->getHeader('Content-type'));
    $this->assertEquals(200, $response->getCode());
  }

  /**
   */
  public function testConstructException() {
    $this->conn->expects($this->once())
      ->method('connect')
      ->will($this->throwException(new Exception("Test")));
    $response = new Response($this->conn);
    $this->assertEquals(Response::EMPTY_RESPONSE, $response->getBody());
    $this->assertEquals(404, $response->getCode());
  }

}
