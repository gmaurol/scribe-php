<?php

class URLUtilsTest extends PHPUnit_Framework_TestCase {

  public function testPercentEncodeMap()
  {
    $params = array();
    $params["key"]="value";
    $params["key with spaces"] = "value with spaces";
    $params["&symbols!"] = "#!";

    $expected = "key=value&key+with+spaces=value+with+spaces&%26symbols%21=%23%21";
    $this->assertEquals($expected, URLUtils::formURLEncodeMap($params));
  }

  public function testReturnEmptyStringForEmptyMap()
  {
    $params = array();
    $expected = "";
    $this->assertEquals($expected, URLUtils::formURLEncodeMap($params));
  }

  public function testFormURLEncodeMapWithMissingValues()
  {
    $params = array();
    $params["key"] = "value";
    $params["key with spaces"] = null;

    $expected = "key=value&key+with+spaces";
    $this->assertEquals($expected, URLUtils::formURLEncodeMap($params));
  }
  
  public function testPercentEncodeString()
  {
    $toEncode = "this is a test &^";
    $expected = "this%20is%20a%20test%20%26%5E";
    $this->assertEquals($expected, URLUtils::percentEncode($toEncode));
  }

  public function testFormURLEncodeString()
  {
    $toEncode = "this is a test &^";
    $expected = "this+is+a+test+%26%5E";
    $this->assertEquals($expected, URLUtils::formURLEncode($toEncode));
  }

  public function testFormURLDecodeString()
  {
    $toDecode = "this+is+a+test+%26%5E";
    $expected = "this is a test &^";
    $this->assertEquals($expected, URLUtils::formURLDecode($toDecode));
  }

  public function testPercentEncodeAllSpecialCharacters()
  {
    $plain = "!*'();:@&=+$,/?#[]";
    $encoded = "%21%2A%27%28%29%3B%3A%40%26%3D%2B%24%2C%2F%3F%23%5B%5D";
    $this->assertEquals($encoded, URLUtils::percentEncode($plain));
    $this->assertEquals($plain, URLUtils::formURLDecode($encoded));
  }

  public function testNotPercentEncodeReservedCharacters()
  {
    $plain = "abcde123456-._~";
    $encoded = $plain;
    $this->assertEquals($encoded, URLUtils::percentEncode($plain));
  }

  /**
   * @expectedException IllegalArgumentException
   */
  public function testThrowExceptionIfStringToEncodeIsNull()
  {
    $toEncode = null;
    URLUtils::percentEncode($toEncode);
  }

  /**
   * @expectedException IllegalArgumentException
   */
  public function testThrowExceptionIfStringToDecodeIsNull()
  {
    $toDecode = null;
    URLUtils::formURLDecode($toDecode);
  }

  /**
   * @expectedException IllegalArgumentException
   */
  public function testThrowExceptionWhenAppendingNullMapToQuerystring()
  {
    $url = "http://www.example.com";
    $nullMap = null;
    URLUtils::appendParametersToQueryString($url, $nullMap);
  }

  public function testAppendNothingToQuerystringIfGivenEmptyMap()
  {
    $url = "http://www.example.com";
    $emptyMap = array();
    $newUrl = URLUtils::appendParametersToQueryString($url, $emptyMap);
    $this->assertEquals($url, $newUrl);
  }

  public function testAppendParametersToSimpleUrl()
  {
    $url = "http://www.example.com";
    $expectedUrl = "http://www.example.com?param1=value1&param2=value+with+spaces";

    $params = array();
    $params["param1"]= "value1";
    $params["param2"]= "value with spaces";

    $url = URLUtils::appendParametersToQueryString($url, $params);
    $this->assertEquals($url, $expectedUrl);
  }

  public function testAppendParametersToUrlWithQuerystring()
  {
    $url = "http://www.example.com?already=present";
    $expectedUrl = "http://www.example.com?already=present&param1=value1&param2=value+with+spaces";

    $params = array();
    $params["param1"]= "value1";
    $params["param2"]= "value with spaces";

    $url = URLUtils::appendParametersToQueryString($url, $params);
    $this->assertEquals($url, $expectedUrl);
  }

  public function testPercentEncodePlusSymbol()
  {
    $plain = "7aEP+jNAwvjc0mjhqg0nuXPf";
    $encoded = "7aEP%2BjNAwvjc0mjhqg0nuXPf";

    $this->assertEquals($encoded, URLUtils::percentEncode($plain));
  }

  public function testURLDecodePlusSymbol()
  {
    $encoded =  "oauth_verifier=7aEP%2BjNAwvjc0mjhqg0nuXPf";
    $expected = "oauth_verifier=7aEP+jNAwvjc0mjhqg0nuXPf";

    $this->assertEquals($expected, URLUtils::formURLDecode($encoded));
  }
}
