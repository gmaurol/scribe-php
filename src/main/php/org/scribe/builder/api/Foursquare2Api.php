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
 * @author Mauro Gonzalez
 */
class Foursquare2Api extends DefaultApi20 {
  const AUTHORIZATION_URL = "https://foursquare.com/oauth2/authenticate?client_id=%s&response_type=code&redirect_uri=%s";

  public function getAccessTokenEndpoint() {
    return "https://foursquare.com/oauth2/access_token?grant_type=authorization_code";
  }

  public function getAuthorizationUrl(OAuthConfig $config) {
    Preconditions::checkValidUrl(
      $config->getCallback(), 
      "Must provide a valid url as callback. Foursquare2 does not support OOB"
    );
    return sprintf(
      self::AUTHORIZATION_URL, 
      $config->getApiKey(), 
      URLUtils::formURLEncode($config->getCallback())
    );
  }

  public function getAccessTokenExtractor() {
    return new JsonTokenExtractor();
  }

}
