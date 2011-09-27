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

class GoogleApi extends DefaultApi10a
{
  const AUTHORIZATION_URL = "https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token=%s";
  
  public function getAccessTokenEndpoint()
  {
    return "https://www.google.com/accounts/OAuthGetAccessToken"; 
  }

  public function getRequestTokenEndpoint()
  {
    return "https://www.google.com/accounts/OAuthGetRequestToken";
  }

  public function getAccessTokenVerb()
  {
    return Verb::GET;
  }

  public function getRequestTokenVerb()
  {
    return Verb::GET;
  }
  
  public function getAuthorizationUrl(Token $requestToken)
  {
    return sprintf(self::AUTHORIZATION_URL, $requestToken->getToken());
  }
}
