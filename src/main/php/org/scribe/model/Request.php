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
 * Represents an HTTP Request object
 * 
 * @author Mauro Gonzalez
 */
class Request
{
  const CONTENT_LENGTH = "Content-Length";

  private $url;
  private $verb;
  private $querystringParams;
  private $bodyParams;
  private $headers;
  private $payload = null;
  private $connection;
  private $charset;
  private $connectTimeout = null;
  private $readTimeout = null;

  /**
   * Creates a new Http Request
   * 
   * @param verb Http Verb (GET, POST, etc)
   * @param url url with optional querystring parameters.
   */
  public function __construct($verb, $url)
  {
    $this->verb = $verb;
    $this->url = $url;
    $this->querystringParams = array();
    $this->bodyParams = array();
    $this->headers = array();
  }

  /**
   * Execute the request and return a {@link Response}
   * 
   * @return Http Response
   * @throws RuntimeException
   *           if the connection cannot be created.
   */
  public function send()
  {
    try
    {
      $this->createConnection();
      return $this->doSend();
    } catch (Exception $ioe)
    {
      throw new OAuthException("Problems while creating connection", $ioe);
    }
  }

  private function createConnection()
  {
    $effectiveUrl = URLUtils::appendParametersToQueryString($this->url, $this->querystringParams);
    if ($this->connection == null)
    {
      $url = new URL($effectiveUrl);
      $this->connection = $url->openConnection();
    }
  }

  public function doSend()
  {
    $this->connection->setRequestMethod($this->verb);
    if ($this->connectTimeout != null) 
    {
      $this->connection->setConnectTimeout($this->connectTimeout);
    }
    if ($this->readTimeout != null)
    {
      $this->connection->setReadTimeout($this->readTimeout);
    }
    $this->addHeaders($this->connection);
    if ($this->verb == Verb::PUT || $this->verb == Verb::POST)
    {
      $this->addBody($this->connection, $this->getBodyContents());
    }
    return new Response($this->connection);
  }

  public function addHeaders(HttpURLConnection $conn)
  {
    foreach ($this->headers as $key => $val )
    {
        $conn->setRequestProperty($key, $val);
    } 
  }

  public function addBody(HttpURLConnection $conn, $content)
  {
    $conn->setRequestProperty(self::CONTENT_LENGTH, strlen($content));
    $conn->setDoOutput(true);
    $conn->write($content);
  }

  /**
   * Add an HTTP Header to the Request
   * 
   * @param key the header name
   * @param value the header value
   */
  public function addHeader($key, $value)
  {
    $this->headers[$key] = $value;
  }

  /**
   * Add a body Parameter (for POST/ PUT Requests)
   * 
   * @param key the parameter name
   * @param value the parameter value
   */
  public function addBodyParameter($key, $value)
  {
    $this->bodyParams[$key] = $value;
  }

  /**
   * Add a QueryString parameter
   *
   * @param key the parameter name
   * @param value the parameter value
   */
  public function addQuerystringParameter($key, $value)
  {
    $this->querystringParams[$key] = $value;
  }

  /**
   * Add body payload.
   * 
   * This method is used when the HTTP body is not a form-url-encoded string,
   * but another thing. Like for example XML.
   * 
   * Note: The contents are not part of the OAuth signature
   * 
   * @param payload the body of the request
   */
  public function addPayload($payload)
  {
    $this->payload = $payload;
  }

  /**
   * Get an {@link Array} of the query string parameters.
   * 
   * @return an associative array containing the query string parameters
   * @throws OAuthException if the URL is not valid
   */
  public function getQueryStringParams()
  {
    try
    {
      $url = new URL($this->url);
      $query = $url->getQuery();
      $params = array();
      $params = array_merge($params, MapUtils::queryStringToMap($query));
      $params = array_merge($params, $this->querystringParams);
      return $params;
    }
    catch (Exception $mue)
    {
      throw new OAuthException("Malformed URL", $mue);
    }
  }

  /**
   * Obtains a {@link Array} of the body parameters.
   * 
   * @return a array containing the body parameters.
   */
  public function getBodyParams()
  {
    return $this->bodyParams;
  }

  /**
   * Obtains the URL of the HTTP Request.
   * 
   * @return the original URL of the HTTP Request
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * Returns the URL without the port and the query string part.
   * 
   * @return the OAuth-sanitized URL
   */
  public function getSanitizedUrl()
  {
    $url = preg_replace("/\\?.*/", "", $this->url);
    $url = preg_replace("/\\:\\d{4}/", "", $url);
    return $url;
  }

  /**
   * Returns the body of the request
   * 
   * @return form encoded string
   * @throws OAuthException if the charset chosen is not supported
   */
  public function getBodyContents()
  {
    return ($this->payload != null) 
            ? $this->payload : URLUtils::formURLEncodeMap($this->bodyParams); 
  }


  /**
   * Returns the HTTP Verb
   * 
   * @return the verb
   */
  public function getVerb()
  {
    return $this->verb;
  }
  
  /**
   * Returns the connection headers as a {@link Map}
   * 
   * @return map of headers
   */
  public function getHeaders()
  {
    return $this->headers;
  }

  /**
   * Returns the connection charset. Defaults to {@link Charset} defaultCharset if not set
   *
   * @return charset
   */
  public function getCharset()
  {
    return $this->charset; 
    // TODO: if not set, return default charset
  }

  /**
   * Sets the connect timeout for the underlying {@link HttpURLConnection}
   * 
   * @param duration duration of the timeout in milliseconds
   *
   */
  public function setConnectTimeout($duration)
  {
    $this->connectTimeout = $duration;
  }

  /**
   * Sets the read timeout for the underlying {@link HttpURLConnection}
   * 
   * @param duration duration of the timeout
   * 
   * @param unit unit of time (milliseconds, seconds, etc)
   */
  public function setReadTimeout($duration)
  {
    $this->readTimeout = $duration;
  }

  /**
   * Set the charset of the body of the request
   *
   * @param charsetName name of the charset of the request
   */
  public function setCharset($charsetName)
  {
    $this->charset = $charsetName;
  }

  /*
   * We need this in order to stub the connection object for test cases
   */
  public function setConnection(HttpURLConnection $connection)
  {
    $this->connection = $connection;
  }

  
  public function __toString()
  {
    return sprintf("@Request(%s %s)", $this->getVerb(), $this->getUrl());
  }
}
