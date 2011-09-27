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
 * Represents an HTTP Response.
 * 
 * @author Mauro Gonzalez
 */
class Response
{
  const EMPTY_RESPONSE = "";

  private $code;
  private $body;
  private $headers;

  public function __construct(HttpURLConnection $connection)
  {
    try
    {
      $connection->connect();
      $this->code = $connection->getResponseCode();
      $this->headers = $this->parseHeaders($connection);
      $this->body = $this->wasSuccessful() ? $connection->getResponseBody() : $connection->getErrors();
    } catch (Exception $e)
    {
      $this->code = 404;
      $this->body = self::EMPTY_RESPONSE;
    }
  }

  private function parseBodyContents()
  {
    // nothing to parse...  
    return $this->body;
  }

  private function parseHeaders(HttpURLConnection $conn)
  {
    
    $this->headers = $conn->getHeaderFields();
    return $this->headers;
    
  }

  private function wasSuccessful()
  {
    return $this->getCode() >= 200 && $this->getCode() < 400;
  }

  /**
   * Obtains the HTTP Response body
   * 
   * @return response body
   */
  public function getBody()
  {
    return $this->body;
  }

  

  /**
   * Obtains the HTTP status code
   * 
   * @return the status code
   */
  public function getCode()
  {
    return $this->code;
  }

  /**
   * Obtains a {@link Map} containing the HTTP Response Headers
   * 
   * @return headers
   */
  public function getHeaders()
  {
    return $this->headers;
  }

  /**
   * Obtains a single HTTP Header value, or null if undefined
   * 
   * @param header
   *          name
   * 
   * @return header value or null
   */
  public function getHeader($name)
  {
    return $this->headers[$name];
  }

}