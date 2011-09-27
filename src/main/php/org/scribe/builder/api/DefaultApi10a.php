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
 * Default implementation of the OAuth protocol, version 1.0a
 * 
 * This class is meant to be extended by concrete implementations of the API,
 * providing the endpoints and endpoint-http-verbs.
 * 
 * If your Api adheres to the 1.0a protocol correctly, you just need to extend 
 * this class and define the getters for your endpoints.
 * 
 * If your Api does something a bit different, you can override the different 
 * extractors or services, in order to fine-tune the process. Please read the 
 * javadocs of the interfaces to get an idea of what to do.
 * 
 * @author Mauro Gonzalez
 *
 */
abstract class DefaultApi10a implements Api
{
  /**
   * Returns the access token extractor.
   * 
   * @return access token extractor
   */
  function getAccessTokenExtractor()
  {
    return new TokenExtractorImpl();
  }

  /**
   * Returns the base string extractor.
   * 
   * @return base string extractor
   */
  public function getBaseStringExtractor()
  {
    return new BaseStringExtractorImpl();
  }

  /**
   * Returns the header extractor.
   * 
   * @return header extractor
   */
  public function getHeaderExtractor()
  {
    return new HeaderExtractorImpl();
  }

  /**
   * Returns the request token extractor.
   * 
   * @return request token extractor
   */
  public function getRequestTokenExtractor()
  {
    return new TokenExtractorImpl();
  }

  /**
   * Returns the signature service.
   * 
   * @return signature service
   */
  public function getSignatureService()
  {
    return new HMACSha1SignatureService(); 
  }

  /**
   * Returns the timestamp service.
   * 
   * @return timestamp service
   */
  public function getTimestampService()
  {
    return new TimestampServiceImpl();
  }
  
  /**
   * Returns the verb for the access token endpoint (defaults to POST)
   * 
   * @return access token endpoint verb
   */
  public function getAccessTokenVerb()
  {
    return Verb::POST;
  }
  
  /**
   * Returns the verb for the request token endpoint (defaults to POST)
   * 
   * @return request token endpoint verb
   */
  public function getRequestTokenVerb()
  {
    return Verb::POST;
  }
  
  /**
   * Returns the URL that receives the request token requests.
   * 
   * @return request token URL
   */
  public abstract function getRequestTokenEndpoint();
  
  /**
   * Returns the URL that receives the access token requests.
   * 
   * @return access token URL
   */
  public abstract function getAccessTokenEndpoint();
  
  /**
   * Returns the URL where you should redirect your users to authenticate
   * your application.
   * 
   * @param requestToken the request token you need to authorize
   * @return the URL where you should redirect your users
   */
  public abstract function getAuthorizationUrl(Token $requestToken);
  
  /**
   * Returns the {@link OAuthService} for this Api
   * 
   * @param apiKey Key
   * @param apiSecret Api Secret
   * @param callback OAuth callback (either URL or 'oob')
   * @param scope OAuth scope (optional) 
   */
  public function createService(OAuthConfig $config)
  {
    return new OAuth10aServiceImpl($this, $config);
  }
}
