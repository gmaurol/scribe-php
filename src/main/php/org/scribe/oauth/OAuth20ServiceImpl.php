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
class OAuth20ServiceImpl implements OAuthService
{
  const VERSION = "2.0";
  
  private $api;
  private $config;
  
  /**
   * Default constructor
   * 
   * @param api OAuth2.0 api information
   * @param config OAuth 2.0 configuration param object
   */
  public function __construct(DefaultApi20 $api, OAuthConfig $config)
  {
    $this->api = $api;
    $this->config = $config;
  }

  /**
   * {@inheritDoc}
   */
  public function getAccessToken(Token $requestToken, Verifier $verifier)
  {
    $request = new OAuthRequest($this->api->getAccessTokenVerb(), 
      $this->api->getAccessTokenEndpoint());
    $request->addQuerystringParameter(OAuthConstants::CLIENT_ID, 
      $this->config->getApiKey());
    $request->addQuerystringParameter(OAuthConstants::CLIENT_SECRET, 
      $this->config->getApiSecret());
    $request->addQuerystringParameter(OAuthConstants::CODE, 
      $verifier->getValue());
    $request->addQuerystringParameter(OAuthConstants::REDIRECT_URI, 
      $this->config->getCallback());
    if($this->config->hasScope()) { 
      $request->addQuerystringParameter(OAuthConstants::SCOPE, 
        $this->config->getScope());
    }
    $response = $request->send();
    return $this->api->getAccessTokenExtractor()->extract($response->getBody());
  }

  /**
   * {@inheritDoc}
   */
  public function getRequestToken()
  {
    throw new UnsupportedOperationException("Unsupported operation, please use " 
      . "'getAuthorizationUrl' and redirect your users there");
  }

  /**
   * {@inheritDoc}
   */
  public function getVersion()
  {
    return self::VERSION;
  }

  /**
   * {@inheritDoc}
   */
  public function signRequest(Token $accessToken, OAuthRequest $request)
  {
    $request->addQuerystringParameter(OAuthConstants::ACCESS_TOKEN, 
      $accessToken->getToken());
  }

  /**
   * {@inheritDoc}
   */
  public function getAuthorizationUrl(Token $requestToken)
  {
    return $this->api->getAuthorizationUrl($this->config);
  }
}
