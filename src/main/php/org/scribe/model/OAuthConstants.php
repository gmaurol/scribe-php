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
 * This class contains OAuth constants, used project-wide
 * 
 * @author Mauro Gonzalez
 */
class OAuthConstants {
  const TIMESTAMP = "oauth_timestamp";
  const SIGN_METHOD = "oauth_signature_method";
  const SIGNATURE = "oauth_signature";
  const CONSUMER_SECRET = "oauth_consumer_secret";
  const CONSUMER_KEY = "oauth_consumer_key";
  const CALLBACK = "oauth_callback";
  const VERSION = "oauth_version";
  const NONCE = "oauth_nonce";
  const PARAM_PREFIX = "oauth_";
  const TOKEN = "oauth_token";
  const TOKEN_SECRET = "oauth_token_secret";
  const OUT_OF_BAND = "oob";
  const VERIFIER = "oauth_verifier";
  const HEADER = "Authorization";
  const SCOPE = "scope";

  public static function getEmptyToken() {
    return new Token("", "");
  }

  //OAuth 2.0
  const ACCESS_TOKEN = "access_token";
  const CLIENT_ID = "client_id";
  const CLIENT_SECRET = "client_secret";
  const REDIRECT_URI = "redirect_uri";
  const CODE = "code";
}
