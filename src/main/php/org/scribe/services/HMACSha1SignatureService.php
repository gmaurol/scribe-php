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
 * HMAC-SHA1 implementation of {@SignatureService}
 * 
 * @author Mauro Gonzalez
 *
 */
class HMACSha1SignatureService implements SignatureService {
  const HASH_ALGO = 'sha1';
  const METHOD = "HMAC-SHA1";

  /**
   * {@inheritDoc}
   */
  public function getSignature($baseString, $apiSecret, $tokenSecret) {
    try {
      Preconditions::checkEmptyString(
        $baseString, "Base string cant be null or empty string"
      );
      Preconditions::checkEmptyString(
        $apiSecret, "Api secret cant be null or empty string"
      );
      return $this->doSign(
          $baseString, URLUtils::percentEncode($apiSecret) . '&'
          . URLUtils::percentEncode($tokenSecret)
      );
    } catch (Exception $e) {
      throw new OAuthSignatureException($baseString, $e);
    }
  }

  private function doSign($toSign, $keyString) {

    if (function_exists('hash_hmac')) {
      $signature = base64_encode(hash_hmac(self::HASH_ALGO, $toSign, $keyString, true));
    } else {
      $blockSize = 64;
      $hashFunction = self::HASH_ALGO;
      if (strlen($keyString) > $blockSize) {
        $keyString = pack('H*', $hashFunction($keyString));
      }
      $keyString = str_pad($keyString, $blockSize, chr(0x00));
      $iPad = str_repeat(chr(0x36), $blockSize);
      $oPad = str_repeat(chr(0x5c), $blockSize);
      $hmac = pack(
        'H*', $hashFunction(
          ($keyString ^ $oPad) . pack(
            'H*', $hashFunction(
              ($keyString ^ $iPad) . $toSign
            )
          )
        )
      );
      $signature = base64_encode($hmac);
    }
    return $signature;
  }

  /**
   * {@inheritDoc}
   */
  public function getSignatureMethod() {
    return self::METHOD;
  }

}
