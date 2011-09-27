<?php

class OAuthRequestTest extends PHPUnit_Framework_TestCase {

  private $subject;
  const TEST_METHOD = 'GET';
  const TEST_URL = 'http://www.test.com/endpoint';

  protected function setup() {
    $this->subject = new OAuthRequest(self::TEST_METHOD, self::TEST_URL);
  }

  /**
   *
   * @param type $k
   * @param type $v 
   * 
   * @dataProvider parametersProvider
   */
  public function testAddOAuthParameter($k, $v, $e) {
    $this->subject->addOAuthParameter($k, $v);
    $this->assertEquals($e, $this->subject->getOauthParameters());
  }

  public function parametersProvider() {
    return array(
      array(
        OAuthConstants::SCOPE, 
        "some", 
        array(OAuthConstants::SCOPE => "some")
      ),
      array(
        OAuthRequest::OAUTH_PREFIX . "a", 
        "b", 
        array(OAuthRequest::OAUTH_PREFIX . 'a' => 'b')
      ),
      array(
        OAuthRequest::OAUTH_PREFIX . "somekey", 
        "somevalue", 
        array(OAuthRequest::OAUTH_PREFIX . 'somekey' => 'somevalue')
      ),
      array(
        OAuthRequest::OAUTH_PREFIX . "foo", 
        "bar", 
        array(OAuthRequest::OAUTH_PREFIX . 'foo' => 'bar')
      )
    );
  }

  /**
   *
   * @param type $k
   * @param type $v 
   * 
   * @expectedException Exception
   * @dataProvider wrongParametersProvider
   */
  public function testAddOauthParameterException($k, $v) {
    $this->subject->addOAuthParameter($k, $v);
  }

  public function wrongParametersProvider() {
    return array(
      array('foo', 'bar'),
      array('test', 'val'),
      array('q', '132'),
    );
  }

  public function testToString() {
    $this->assertEquals(
      sprintf(
        "@OAuthRequest(%s, %s)", 
        self::TEST_METHOD, 
        self::TEST_URL
      ), 
      (string) $this->subject
    );
  }

}