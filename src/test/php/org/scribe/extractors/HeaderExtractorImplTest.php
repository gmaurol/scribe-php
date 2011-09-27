<?php

class HeaderExtractorImplTest extends PHPUnit_Framework_TestCase {

  private $extractor;
  private $request;

  protected function setUp() {
    $this->extractor = new HeaderExtractorImpl();
    $this->request = new OAuthRequest(Verb::GET, "http://example.com");
    $this->request->addOAuthParameter(OAuthConstants::TIMESTAMP, "123456");
    $this->request->addOAuthParameter(OAuthConstants::CONSUMER_KEY, "AS#$^*@&");
    $this->request->addOAuthParameter(OAuthConstants::CALLBACK, "http://example/callback");
    $this->request->addOAuthParameter(OAuthConstants::SIGNATURE, "OAuth-Signature");
  }

  public function testExtractStandardHeader()
  {
    $expected = 'OAuth oauth_callback="http%3A%2F%2Fexample%2Fcallback", oauth_consumer_key="AS%23%24%5E%2A%40%26", oauth_signature="OAuth-Signature", oauth_timestamp="123456"';
    $header = $this->extractor->extract($this->request);
    $this->assertEquals($expected, $header);
  }

  /**
   * @expectedException OAuthParametersMissingException
   */
  public function testExceptionIfRequestHasNoOAuthParams()
  {
    $emptyRequest = new OAuthRequest(Verb::GET, "http://example.com");
    $this->extractor->extract($emptyRequest);
  }
}