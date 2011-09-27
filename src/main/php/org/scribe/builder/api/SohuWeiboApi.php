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
class SohuWeiboApi extends DefaultApi10a {
  const REQUEST_TOKEN_URL = "http://api.t.sohu.com/oauth/request_token";
  const ACCESS_TOKEN_URL = "http://api.t.sohu.com/oauth/access_token";
  const AUTHORIZE_URL = "http://api.t.sohu.com/oauth/authorize?oauth_token=%s";

  public function getRequestTokenEndpoint() {
    return self::REQUEST_TOKEN_URL;
  }

  public function getAccessTokenEndpoint() {
    return self::ACCESS_TOKEN_URL;
  }

  public function getAuthorizationUrl(Token $requestToken) {
    return sprintf(self::AUTHORIZE_URL, $requestToken->getToken());
  }
}
