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
 * OAuth 1.0a implementation of {@link OAuthService}
 * 
 * @author Mauro Gonzalez
 */
class OAuth10aServiceImpl implements OAuthService
{
  const VERSION = "1.0";

  private $config;
  private $api;

  /**
   * Default constructor
   * 
   * @param api OAuth1.0a api information
   * @param config OAuth 1.0a configuration param object
   */
  public function __construct(DefaultApi10a $api, OAuthConfig $config)
  {
    $this->api = $api;
    $this->config = $config;
  }

  /**
   * {@inheritDoc}
   */
  public function getRequestToken()
  {
    $request = new OAuthRequest(
      $this->api->getRequestTokenVerb(), 
      $this->api->getRequestTokenEndpoint()
    );
    $request->addOAuthParameter(
      OAuthConstants::CALLBACK, 
      $this->config->getCallback()
    );
    $this->addOAuthParams($request, OAuthConstants::getEmptyToken());
    $this->addSignature($request);
    $response = $request->send();
    return $this->api->getRequestTokenExtractor()
      ->extract($response->getBody());
  }

  private function addOAuthParams(OAuthRequest $request, Token $token)
  {
    $request->addOAuthParameter(OAuthConstants::TIMESTAMP, 
      $this->api->getTimestampService()->getTimestampInSeconds());
    $request->addOAuthParameter(OAuthConstants::NONCE, 
      $this->api->getTimestampService()->getNonce());
    $request->addOAuthParameter(OAuthConstants::CONSUMER_KEY, 
      $this->config->getApiKey());
    $request->addOAuthParameter(OAuthConstants::SIGN_METHOD, 
      $this->api->getSignatureService()->getSignatureMethod());
    $request->addOAuthParameter(OAuthConstants::VERSION, $this->getVersion());
    if($this->config->hasScope()) {
      $request->addOAuthParameter(OAuthConstants::SCOPE, 
        $this->config->getScope());
    }
    $request->addOAuthParameter(OAuthConstants::SIGNATURE, 
      $this->getSignature($request, $token));
  }

  /**
   * {@inheritDoc}
   */
  public function getAccessToken(Token $requestToken, Verifier $verifier)
  {
    $request = new OAuthRequest($this->api->getAccessTokenVerb(), 
      $this->api->getAccessTokenEndpoint());
    $request->addOAuthParameter(OAuthConstants::TOKEN, 
      $requestToken->getToken());
    $request->addOAuthParameter(OAuthConstants::VERIFIER, 
      $verifier->getValue());
    $this->addOAuthParams($request, $requestToken);
    $this->addSignature($request);
    $response = $request->send();
    return $this->api->getAccessTokenExtractor()->extract($response->getBody());
  }

  /**
   * {@inheritDoc}
   */
  public function signRequest(Token $token, OAuthRequest $request)
  {
    $request->addOAuthParameter(OAuthConstants::TOKEN, $token->getToken());
    $this->addOAuthParams($request, $token);
    $this->addSignature($request);
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
  public function getAuthorizationUrl(Token $requestToken)
  {
    return $this->api->getAuthorizationUrl($requestToken);
  }
  
  private function getSignature(OAuthRequest $request, Token $token)
  {
    $baseString = $this->api->getBaseStringExtractor()->extract($request);
    return $this->api->getSignatureService()
      ->getSignature(
        $baseString, 
        $this->config->getApiSecret(), 
        $token->getSecret()
      );
  }

  private function addSignature(OAuthRequest $request)
  {
    $signatureType = $this->config->getSignatureType();
    switch ($signatureType)
    {
      case SignatureType::HEADER:
        $oauthHeader = $this->api->getHeaderExtractor()->extract($request);
        $request->addHeader(OAuthConstants::HEADER, $oauthHeader);
        break;
      case SignatureType::QUERY_STRING:
        $oauthParams = $request->getOauthParameters();
        foreach ($oauthParams as $key => $val)
        {
          $request->addQuerystringParameter($key, $val);
        }
        break;
    }
  }
}
