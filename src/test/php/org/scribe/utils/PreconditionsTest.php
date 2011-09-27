<?php

class PreconditionsTest extends PHPUnit_Framework_TestCase {

  public function testCheckNotNull() {
    try {
      Preconditions::checkNotNull(new stdClass(), "error");
      $this->assertTrue(true);
    } catch (IllegalArgumentException $e) {
      $this->fail();
    }
  }

  /**
   * @expectedException        IllegalArgumentException
   * @expectedExceptionMessage checkNotNull failed
   */
  public function testCheckNotNullException() {
    Preconditions::checkNotNull(null, "checkNotNull failed");
  }

  public function testCheckEmptyString() {
    try {
      Preconditions::checkEmptyString(" hola", "CheckEmptyString err");
      $this->assertTrue(true);
    } catch (IllegalArgumentException $e) {
      $this->fail();
    }
  }

  /**
   * @expectedException        IllegalArgumentException
   * @expectedExceptionMessage CheckEmptyString err
   */
  public function testCheckEmptyStringException() {
    Preconditions::checkEmptyString(" ", "CheckEmptyString err");
  }

  /**
   * @dataProvider validURLProvider
   */
  public function testCheckValidUrl($url) {
    try {
      Preconditions::checkValidUrl($url, "invalid url");
      $this->assertTrue(true);
    } catch (IllegalArgumentException $e) {
      $this->fail();
    }
  }

  /**
   *
   * @param type $url 
   * 
   * @dataProvider invalidURLProvider
   * @expectedException        IllegalArgumentException
   * @expectedExceptionMessage invalid url
   */
  public function testCheckValidUrlException($url) {
    Preconditions::checkValidUrl($url, "invalid url");
  }

  /**
   *
   * @param type $url 
   * 
   * @dataProvider validOauthCallbackProvider
   */
  public function testCheckValidOAuthCallback($url) {
    try {
      Preconditions::checkValidOAuthCallback($url, "invalid callback");
      $this->assertTrue(true);
    } catch (IllegalArgumentException $e) {
      $this->fail();
    }
  }

  /**
   *
   * @param type $url 
   * 
   * @dataProvider invalidURLProvider
   * @expectedException        IllegalArgumentException
   * @expectedExceptionMessage invalid callback
   */
  public function testCheckValidOAuthCallbackException($url) {
    Preconditions::checkValidOAuthCallback($url, "invalid callback");
  }

  public function invalidURLProvider() {
    return array(
      array('obviously wrong'),
      array('http://sa+sa.net.test'),
      array('http://as..sa.com'),
      array('http://assa sasa.com'),
      array('http://-assa sasa.com')
    );
  }

  public function validURLProvider() {
    return array(
      array('http://domain.com/test'),
      array('http://sub.domain.com'),
      array('http://www.domain.com'),
      array('http://test.com.ar'),
      array('https://test.com.ar')
    );
  }

  public function validOauthCallbackProvider() {
    return array(
      array('http://domain.com/test'),
      array('http://sub.domain.com'),
      array('http://www.domain.com'),
      array('http://test.com.ar'),
      array('https://test.com.ar'),
      array(OAuthConstants::OUT_OF_BAND)
    );
  }

}