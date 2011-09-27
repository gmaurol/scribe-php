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
 * Utils for {@link Array} manipulation
 * 
 * @author Mauro Gonzalez
 */
class MapUtils {

  const EMPTY_STRING = "";
  const PAIR_SEPARATOR = "=";
  const PARAM_SEPARATOR = "&";

  /**
   * Sorts a Map
   * 
   * @param map unsorted map
   * @return sorted map
   * 
   */
  public static function sort($map) {
    Preconditions::checkNotNull($map, "Cannot sort a null object.");
    ksort($map);
    return $map;
  }

  /**
   * Form-urlDecodes and appends all keys from the source {@link Array} to the
   * target {@link Array}
   *
   * @param source Array from where the keys get copied and decoded
   * @param target Array where the decoded keys are copied to
   */
  public static function decodeAndAppendEntries(array $source, array &$target) {
    foreach ($source as $key => $value) {
      $target[URLUtils::percentEncode($key)] = URLUtils::percentEncode($value);
    }
  }

  /**
   * Concats a key-value map into a querystring-like String
   *
   * @param params key-value map
   * @return querystring-like String
   */
  public static function concatSortedPercentEncodedParams(array $params) {
    $result = "";
    foreach ($params as $key => $value)
    {
      $result .= $key . self::PAIR_SEPARATOR;
      $result .= $value .  self::PARAM_SEPARATOR;
    }
    return substr($result, 0, strlen($result) -1);
  }

  /**
   * Parses and form-urldecodes a querystring-like string into a map
   *
   * @param queryString querystring-like String
   * @return a map with the form-urldecoded parameters
   */
  public static function queryStringToMap($queryString) {
    $result = array();

    if ($queryString != null && strlen($queryString) > 0) {
      $pairs = explode(self::PARAM_SEPARATOR, $queryString);
      foreach ($pairs as $pair) {
        $keyVal = explode(self::PAIR_SEPARATOR, $pair);
        $key = URLUtils::formURLDecode($keyVal[0]);
        $value = count($keyVal) > 1 ? URLUtils::formURLDecode($keyVal[1]) : self::EMPTY_STRING;
        $result[$key] = $value;
      }
    }
    return $result;
  }

}
