<?php

class JsonTokenExtractorTest extends PHPUnit_Framework_TestCase {

  private $extractor;

  protected function setUp() {
    $this->extractor = new JsonTokenExtractor();
  }

  /**
   * @dataProvider responsesProvider
   */
  public function testExtract($response, $expected) {
    $token = $this->extractor->extract($response);
    $this->assertTrue($token instanceof Token);
    $this->assertEquals($expected, $token->getToken());
  }
  /**
   * @expectedException OAuthException
   * @expectedExceptionMessage Cannot extract an acces token. Response was: invalidResponse
   */
  public function testExtractException() {
    $token = $this->extractor->extract('invalidResponse');
  }

  public function responsesProvider() {
    return array(
      array('"access_token":"sometoken"', 'sometoken'),
      array('"access_token":"%$&/%$/&%$/%$/&%$"', '%$&/%$/&%$/%$/&%$'),
      array('"access_token":"1234568797"', '1234568797'),
      array('"access_token":"DSADSADSADSA$·"', 'DSADSADSADSA$·')
    );
    // TODO: is this test ok?
  }

}
