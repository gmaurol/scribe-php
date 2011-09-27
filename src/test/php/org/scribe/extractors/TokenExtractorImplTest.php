<?php

class TokenExtractorImplTest extends PHPUnit_Framework_TestCase {

  private $extractor;

  protected function setUp() {
    $this->extractor = new TokenExtractorImpl();
  }

  /**
   * @dataProvider responsesProvider
   */
  public function testExtract($response, $expected_token, $expected_secret) {
    $token = $this->extractor->extract($response);
    $this->assertTrue($token instanceof Token);
    $this->assertEquals($expected_token, $token->getToken());
    $this->assertEquals($expected_secret, $token->getSecret());
  }
  /**
   * @expectedException OAuthException
   * @expectedExceptionMessage Can't extract token and secret from this: 'invalidResponse'
   */
  public function testExtractException() {
    $token = $this->extractor->extract('invalidResponse');
  }

  public function responsesProvider() {
    return array(
      array(
        'oauth_token=sometoken&oauth_token_secret=somesecret', 
        'sometoken', 
        'somesecret'
      ),
      array(
        'oauth_token=sometoken&key=val&oauth_token_secret=somesecret', 
        'sometoken', 
        'somesecret'
      ),
      array(
        'key2=val2&oauth_token_secret=somesecret&oauth_token=sometoken&key=val', 
        'sometoken', 
        'somesecret'
      ),
    );
  }

}
