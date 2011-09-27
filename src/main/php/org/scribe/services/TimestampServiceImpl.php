<?php

/*
 * 
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
 * 
 */

/**
 * Implementation of {@link TimestampService} using plain java classes.
 * 
 * @author Mauro Gonzalez
 */
class TimestampServiceImpl implements TimestampService {

  private $timer;

  /**
   * Default constructor. 
   */
  public function __construct() {
    $this->timer = new Timer();
  }

  /**
   * {@inheritDoc}
   */
  public function getNonce() {
    $ts = $this->timer->getTs();
    return (string) $ts +  $this->timer->getRandomInteger();
  }

  /**
   * {@inheritDoc}
   */
  public function getTimestampInSeconds() {
    return (string) $this->timer->getTs();
  }

  public function setTimer(Timer $timer) {
    $this->timer = $timer;
  }

}

/**
 * Inner class for generating the timestamps.
 * 
 * @author Mauro Gonzalez
 */
class Timer {

  public function getTs() {
    return time();
  }

  public function getRandomInteger() {
    return mt_rand();
  }
}
