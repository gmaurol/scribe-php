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
 * Implementation of the HttpURLConnectionCurl class
 * 
 * @author Mauro Gonzalez
 */

class HttpURLConnectionCurl implements HttpURLConnection {

  private $url;
  private $requestMethod;
  private $connectionTimeout = 30000;
  private $readTimeout = 30000;
  private $requestProperties = array();
  private $doOutput = true;
  private $body;
  private $responseBody;
  private $responseHeaders;
  private $errors;
  private $code;
  private $requestInfo;
  private $rawResponse;
  private $ch;

  public function __construct($url) {
    $this->url = $url;
  }

  public function connect() {
    $this->ch = curl_init($this->url);
    curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
    curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->readTimeout);
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, $this->doOutput);
    curl_setopt($this->ch, CURLOPT_HEADER, 1);
    curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
    
    if ($this->requestProperties) {
      //
      curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->requestProperties);
    }
    
    if ($this->requestMethod == Verb::POST) {
      curl_setopt($this->ch, CURLOPT_POST, 1);
      curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->body);
    }

    if ($this->requestMethod == Verb::PUT) {
      curl_setopt($this->ch, CURLOPT_PUT, 1);
      curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->body);
    }
    // TODO handle delete, 
    $this->rawResponse = curl_exec($this->ch);
    $this->requestInfo = curl_getinfo($this->ch);
    $this->responseBody = $this->extractBody();
    $this->responseHeaders = $this->extractHeaders();
    if (curl_errno($this->ch)) {
      $this->errors = curl_error($this->ch);
    }

    $this->code = $this->extractCode();
    curl_close($this->ch);
    // TODO: add proxy settings
  }

  public function setRequestMethod($method) {
    $this->requestMethod = $method;
  }

  public function setConnectTimeout($t) {
    $this->connectionTimeout = $t;
  }

  public function setReadTimeout($t) {
    $this->readTimeout = $t;
  }

  public function setRequestProperty($key, $val) {
    $this->requestProperties[] = sprintf("%s: %s", $key, $val);
  }

  public function setDoOutput($boolean) {
    $this->doOutput = $boolean;
  }

  public function write($content) {
    $this->body = $content;
  }

  public function getResponseBody() {
    return $this->responseBody;
  }

  public function getErrors() {
    return $this->errors;
  }

  public function getResponseCode() {
    return $this->code;
  }

  public function getHeaderFields() {
    return $this->responseHeaders;
  }

  private function extractBody() {
    return trim(substr($this->rawResponse, $this->requestInfo['header_size']));
  }

  private function extractHeaders() {
    $headers = array();
    $rawHeader = substr($this->rawResponse, 0, $this->requestInfo['header_size']);
    $headerLines = explode("\r\n", trim($rawHeader));
    array_shift($headerLines);
    foreach ($headerLines as $header) {
      if ($header != "") {
        $key = substr($header, 0, strpos($header, ":"));
        $val = substr($header, strpos($header, ":") + 1);
        $headers[$key] = $val;
      }
    }
    return $headers;
  }

  private function extractCode() {
    return $this->requestInfo['http_code'];
  }

}
