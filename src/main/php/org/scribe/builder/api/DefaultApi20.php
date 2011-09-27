<?php

/**
 * Default implementation of the OAuth protocol, version 2.0 (draft 11)
 *
 * This class is meant to be extended by concrete implementations of the API,
 * providing the endpoints and endpoint-http-verbs.
 *
 * If your Api adheres to the 2.0 (draft 11) protocol correctly, you just need to extend
 * this class and define the getters for your endpoints.
 *
 * If your Api does something a bit different, you can override the different
 * extractors or services, in order to fine-tune the process. Please read the
 * javadocs of the interfaces to get an idea of what to do.
 *
 * @author Diego Silveira
 *
 */
abstract class DefaultApi20 implements Api {

  /**
   * Returns the access token extractor.
   * 
   * @return access token extractor
   */
  public function getAccessTokenExtractor() {
    return new TokenExtractor20Impl();
  }

  /**
   * Returns the verb for the access token endpoint (defaults to GET)
   * 
   * @return access token endpoint verb
   */
  public function getAccessTokenVerb() {
    return Verb::GET;
  }

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
   * @param config OAuth 2.0 configuration param object
   * @return the URL where you should redirect your users
   */
  public abstract function getAuthorizationUrl(OAuthConfig $config);

  /**
   * {@inheritDoc}
   */
  public function createService(OAuthConfig $config) {
    return new OAuth20ServiceImpl($this, $config);
  }

}