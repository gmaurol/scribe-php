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
 * Default implementation of {@RequestTokenExtractor} and {@AccessTokenExtractor}. Conforms to OAuth 1.0a
 *
 * The process for extracting access and request tokens is similar so this class can do both things.
 * 
 * @author Mauro Gonzalez
 */
class TokenExtractorImpl implements RequestTokenExtractor {
  const TOKEN_REGEX = "/oauth_token=([^&]+)/";
  const SECRET_REGEX = "/oauth_token_secret=([^&]+)/";

  /**
   * {@inheritDoc} 
   */
  public function extract($response) {
    Preconditions::checkEmptyString($response, "Response body is incorrect. " 
      . "Can't extract a token from an empty string");
    $token = $this->_extract($response, self::TOKEN_REGEX);
    $secret = $this->_extract($response, self::SECRET_REGEX);
    return new Token($token, $secret, $response);
  }

  private function _extract($response, $p) {

    if (preg_match($p, $response, $matches) && count($matches) >= 1) {
      return URLUtils::formURLDecode($matches[1]);
    } else {
      throw new OAuthException("Response body is incorrect. Can't extract token" 
        . " and secret from this: '" . $response . "'", null);
    }
  }

}
