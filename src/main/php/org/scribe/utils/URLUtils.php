<?php

/*
 * The MIT License (MIT)
 * Copyright (c) 2011 Mauro Gonzalez 
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
 * copies of the Software, and to permit persons to whom the Software is 
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN 
 * THE SOFTWARE.
 */

/**
 * Utils to deal with URL and url-encodings
 *
 * @author Mauro Gonzalez
 */
class URLUtils
{
  const EMPTY_STRING = "";
  const PAIR_SEPARATOR = "=";
  const PARAM_SEPARATOR = "&";
  const QUERY_STRING_SEPARATOR = '?';

  //const ERROR_MSG = sprintf("Cannot find specified encoding: %s", UTF_8);

  //const ENCODING_RULES;

  private static $ENCODING_RULES = array(
      array("*","%2A"),
      array("+","%20"),
      array("%7E", "~")
  );

  /**
   * Turns a map into a form-urlencoded string
   * 
   * @param map any map
   * @return form-url-encoded string
   */
  public static function formURLEncodeMap($map)
  {
    Preconditions::checkNotNull($map, "Cannot url-encode a null object");
    return $map ? self::doFormUrlEncode($map) : self::EMPTY_STRING;
  }

  private static function doFormUrlEncode(array $map)
  {
    $encodedString = "";
    foreach ($map as $key => $value)
    {
      $encodedString .= self::PARAM_SEPARATOR . self::formURLEncode($key);
      if($map[$key])
      {
        $encodedString .= self::PAIR_SEPARATOR . self::formURLEncode($map[$key]);
      }
    }
    return substr($encodedString, 1);    
  }

  /**
   * Percent encodes a string
   * 
   * @param string plain string
   * @return percent encoded string
   */
  public static function percentEncode($string)
  {
    $encoded = self::formURLEncode($string);
    foreach (self::$ENCODING_RULES as $r)
    {
      $rule = new EncodingRule($r);  
      $rule->apply($encoded);
    }
    return $encoded;
  }

  /**
   * Translates a string into application/x-www-form-urlencoded format
   *
   * @param plain
   * @return form-urlencoded string
   */
  public static function formURLEncode($string)
  {
    Preconditions::checkNotNull($string, "Cannot encode null string");
    return urlencode($string);
  }

  /**
   * Decodes a application/x-www-form-urlencoded string
   * 
   * @param string form-urlencoded string
   * @return plain string
   */
  public static function formURLDecode($string)
  {
    Preconditions::checkNotNull($string, "Cannot decode null string");
    return urldecode($string);
  }

  /**
   * Append given parameters to the query string of the url
   *
   * @param url the url to append parameters to
   * @param params any map
   * @return new url with parameters on query string
   */
  public static function appendParametersToQueryString($url, $params)
  {
    Preconditions::checkNotNull($url, "Cannot append to null URL");
    $queryString = self::formURLEncodeMap($params);
    if (!$queryString)
    {
      return $url;
    }
    else
    {
      $url .= strpos($url, self::QUERY_STRING_SEPARATOR) !== false 
              ? self::PARAM_SEPARATOR : self::QUERY_STRING_SEPARATOR;
      $url .= $queryString;
      return $url;
      
    }
  }
}

final class EncodingRule {
    private $ch;
    private $toCh;

    /**
     * constructor 
     * 
     * @param array array(char, charTo)
     */
    public function __construct(array $rule)
    {
      $this->ch = $rule[0];
      $this->toCh = $rule[1];
    }
    /**
     * applies the rule to the given string
     * @param string string
     */
    public function apply(&$string) {
      $string = str_replace($this->ch, $this->toCh, $string);
    }
  }