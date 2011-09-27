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
 * Implementation of the Builder pattern, with a fluent interface that creates a
 * {@link OAuthService}
 * 
 * @author Mauro Gonzalez
 *
 */
class ServiceBuilder {

  private $apiKey;
  private $apiSecret;
  private $callback;
  private $api;
  private $scope;
  private $signatureType = SignatureType::HEADER;

  /**
   * Default constructor
   */
  public function __construct() {
    $this->callback = OAuthConstants::OUT_OF_BAND;
  }

  /**
   * Configures the {@link Api}
   * 
   * @param apiClass the class of one of the existent {@link Api}s on org.scribe.api package
   * @return the {@link ServiceBuilder} instance for method chaining
   */
  public function provider($apiClass) {
    if ($apiClass instanceof Api) {
      $this->_provider($apiClass);
    } else if (is_string($apiClass)) {
      $this->api = $this->createApi($apiClass);
    }
    return $this;
  }

  private function createApi($apiClass) {
    Preconditions::checkEmptyString($apiClass, "Api class cannot be empty");
    $api = null;
    try {
      $api = new $apiClass();
    } catch (Exception $e) {
      throw new OAuthException("Error while creating the Api object", $e);
    }
    return $api;
  }

  /**
   * Configures the {@link Api}
   *
   * Overloaded version. Let's you use an instance instead of a class.
   *
   * @param api instance of {@link Api}s
   * @return the {@link ServiceBuilder} instance for method chaining
   */
  private function _provider(Api $api) {
    Preconditions::checkNotNull($api, "Api cannot be null");
    $this->api = $api;
    return $this;
  }

  /**
   * Adds an OAuth callback url
   * 
   * @param callback callback url. Must be a valid url or 'oob' for out of band OAuth
   * @return the {@link ServiceBuilder} instance for method chaining
   */
  public function callback($callback) {
    Preconditions::checkValidOAuthCallback(
      $callback, "Callback must be a valid URL or 'oob'"
    );
    $this->callback = $callback;
    return $this;
  }

  /**
   * Configures the api key
   * 
   * @param apiKey The api key for your application
   * @return the {@link ServiceBuilder} instance for method chaining
   */
  public function apiKey($apiKey) {
    Preconditions::checkEmptyString($apiKey, "Invalid Api key");
    $this->apiKey = $apiKey;
    return $this;
  }

  /**
   * Configures the api secret
   * 
   * @param apiSecret The api secret for your application
   * @return the {@link ServiceBuilder} instance for method chaining
   */
  public function apiSecret($apiSecret) {
    Preconditions::checkEmptyString($apiSecret, "Invalid Api secret");
    $this->apiSecret = $apiSecret;
    return $this;
  }

  /**
   * Configures the OAuth scope. This is only necessary in some APIs (like Google's).
   * 
   * @param scope The OAuth scope
   * @return the {@link ServiceBuilder} instance for method chaining
   */
  public function scope($scope) {
    Preconditions::checkEmptyString($scope, "Invalid OAuth scope");
    $this->scope = $scope;
    return $this;
  }

  /**
   * Configures the signature type, choose between header, querystring, etc. Defaults to Header
   *
   * @param scope The OAuth scope
   * @return the {@link ServiceBuilder} instance for method chaining
   */
  public function signatureType($type) {
    Preconditions::checkNotNull($type, "Signature type can't be null");
    $this->signatureType = $type;
    return $this;
  }

  /**
   * Returns the fully configured {@link OAuthService}
   * 
   * @return fully configured {@link OAuthService}
   */
  public function build() {
    Preconditions::checkNotNull(
      $this->api, "You must specify a valid api through the provider() method"
    );
    Preconditions::checkEmptyString(
      $this->apiKey, "You must provide an api key"
    );
    Preconditions::checkEmptyString(
      $this->apiSecret, "You must provide an api secret"
    );
    return $this->api->createService(
        new OAuthConfig(
          $this->apiKey,
          $this->apiSecret,
          $this->callback,
          $this->signatureType,
          $this->scope
        )
    );
  }

}
