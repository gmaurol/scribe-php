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
class TwitterApi extends DefaultApi10a {
  const AUTHORIZE_URL = "https://api.twitter.com/oauth/authorize?oauth_token=%s";
  const REQUEST_TOKEN_RESOURCE = "api.twitter.com/oauth/request_token";
  const ACCESS_TOKEN_RESOURCE = "api.twitter.com/oauth/access_token";

  public function getAccessTokenEndpoint() {
    return "http://" . self::ACCESS_TOKEN_RESOURCE;
  }

  public function getRequestTokenEndpoint() {
    return "http://" . self::REQUEST_TOKEN_RESOURCE;
  }

  public function getAuthorizationUrl(Token $requestToken) {
    return sprintf(self::AUTHORIZE_URL, $requestToken->getToken());
  }

}

class TwitterApiSSL extends TwitterApi {

  public function getAccessTokenEndpoint() {
    return "https://" . self::ACCESS_TOKEN_RESOURCE;
  }

  public function getRequestTokenEndpoint() {
    return "https://" . self::REQUEST_TOKEN_RESOURCE;
  }
}
