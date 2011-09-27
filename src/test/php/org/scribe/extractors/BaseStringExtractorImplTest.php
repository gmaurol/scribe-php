<?php

class BaseStringExtractorImplTest extends PHPUnit_Framework_TestCase {

  private $extractor;
  private $request;

  protected function setUp() {
    $this->extractor = new BaseStringExtractorImpl();
    $this->request = new OAuthRequest(Verb::GET, "http://example.com");
    $this->request->addOAuthParameter(OAuthConstants::TIMESTAMP, "123456");
    $this->request->addOAuthParameter(OAuthConstants::CONSUMER_KEY, "AS#$^*@&");
    $this->request->addOAuthParameter(OAuthConstants::CALLBACK, "http://example/callback");
    $this->request->addOAuthParameter(OAuthConstants::SIGNATURE, "OAuth-Signature");
  }
    
    
    
 
  public function testExtract() {
    $expected = "GET&http%3A%2F%2Fexample.com&oauth_callback%3Dhttp%253A%252F%252Fexample%252Fcallback%26oauth_consumer_key%3DAS%2523%2524%255E%252A%2540%2526%26oauth_signature%3DOAuth-Signature%26oauth_timestamp%3D123456";
    $actual = $this->extractor->extract($this->request);
    $this->assertEquals($expected, $actual);
  }
  
  
  
  /**
   * @expectedException OAuthParametersMissingException
   */
  public function testThrowExceptionIfRquestHasNoOAuthParameters()
  {
    $request = new OAuthRequest(Verb::GET, "http://example.com");
    $this->extractor->extract($request);
  }
  
  public function testProperlyEncodeSpaces()
  {
    $expected = "GET&http%3A%2F%2Fexample.com&body%3Dthis%2520param%2520has%2520whitespace%26oauth_callback%3Dhttp%253A%252F%252Fexample%252Fcallback%26oauth_consumer_key%3DAS%2523%2524%255E%252A%2540%2526%26oauth_signature%3DOAuth-Signature%26oauth_timestamp%3D123456";
    $this->request->addBodyParameter("body", "this param has whitespace");
    $this->assertEquals($expected, $this->extractor->extract($this->request));
  }
}