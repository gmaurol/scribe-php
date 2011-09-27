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
 * The representation of an OAuth HttpRequest.
 * 
 * Adds OAuth-related functionality to the {@link Request}  
 * 
 * @author Mauro Gonzalez
 */
class OAuthRequest extends Request {
  const OAUTH_PREFIX = "oauth_";
  private $oauthParameters = array();

  /**
   * Default constructor.
   * 
   * @param verb Http verb/method
   * @param url resource URL
   */
  public function __construct($verb, $url) {
    parent::__construct($verb, $url);
    $this->oauthParameters = array();
  }

  /**
   * Adds an OAuth parameter.
   * 
   * @param key name of the parameter
   * @param value value of the parameter
   * 
   * @throws Exception if the parameter is not an OAuth parameter
   */
  public function addOAuthParameter($key, $value) {
    $this->oauthParameters[$this->checkKey($key)] = $value;
  }

  private function checkKey($key) {
    if (strpos($key, self::OAUTH_PREFIX) === 0
      || $key === OAuthConstants::SCOPE) {
      return $key;
    } else {
      throw new Exception(sprintf("OAuth parameters must either be '%s' or "
          . "start with '%s'", OAuthConstants::SCOPE, self::OAUTH_PREFIX));
    }
  }

  /**
   * Returns the array containing the key-value pair of parameters.
   * 
   * @return parameters as map
   */
  public function getOauthParameters() {
    return $this->oauthParameters;
  }

  public function __toString() {
    return sprintf("@OAuthRequest(%s, %s)", $this->getVerb(), $this->getUrl());
  }

}