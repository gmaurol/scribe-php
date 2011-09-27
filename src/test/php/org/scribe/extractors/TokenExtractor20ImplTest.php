<?php

class TokenExtractor20ImplTest extends PHPUnit_Framework_TestCase {

  private $extractor;

  protected function setUp() {
    $this->extractor = new TokenExtractor20Impl();
  }

  /**
   * @dataProvider responsesProvider
   */
  public function testExtract($response, $expected_token) {
    $token = $this->extractor->extract($response);
    $this->assertTrue($token instanceof Token);
    $this->assertEquals($expected_token, $token->getToken());
    $this->assertEquals("", $token->getSecret());
  }
  /**
   * @expectedException OAuthException
   * @expectedExceptionMessage Can't extract a token from this: '&expires=5108'
   */
  public function testExtractException() {
    $token = $this->extractor->extract('&expires=5108');
  }

  public function responsesProvider() {
    return array(
      array('access_token=166942940015970|2.2ltzWXYNDjCtg5ZDVVJJeg__.3600.12958' 
            . '16400-548517159|RsXNdKrpxg8L6QNLWcs2TVTmcaE&expires=5108', 
            '166942940015970|2.2ltzWXYNDjCtg5ZDVVJJeg__.3600.1295816400-5485171' 
            . '59|RsXNdKrpxg8L6QNLWcs2TVTmcaE'
      ),
      array('access_token=166942940015970|2.2ltzWXYNDjCtg5ZDVVJJeg__.3600.12958' 
            . '16400-548517159|RsXNdKrpxg8L6QNLWcs2TVTmcaE', 
            '166942940015970|2.2ltzWXYNDjCtg5ZDVVJJeg__.3600.1295816400-5485171' 
            . '59|RsXNdKrpxg8L6QNLWcs2TVTmcaE'),
    );
  }

}
