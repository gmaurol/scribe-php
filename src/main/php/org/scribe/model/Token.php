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
 * Represents an OAuth token (either request or access token) and its secret
 * 
 * @author Mauro Gonzalez
 */
class Token
{
	
  private $token;
  private $secret;
  private $rawResponse;

  /**
   * Default constructor
   * 
   * @param token token value
   * @param secret token secret
   */
  public function __construct($token, $secret, $rawResponse = null)
  {
    $this->token = $token;
    $this->secret = $secret;
    $this->rawResponse = $rawResponse;
  }

  public function getToken()
  {
    return $this->token;
  }

  public function getSecret()
  {
    return $this->secret;
  }

  public function getRawResponse()
  {
    if ($this->rawResponse == null)
    {
      throw new Exception("This token object was not constructed by scribe and does not have a rawResponse");
    }
    return $this->rawResponse;
  }

  public function __toString()
  {
    return sprintf("Token[%s , %s]", $this->token, $this->secret);
  }
}
