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
 * Default implementation of {@link BaseStringExtractor}. Conforms to OAuth 1.0a
 * 
 * @author Mauro Gonzalez
 *
 */
class BaseStringExtractorImpl implements BaseStringExtractor {
  const AMPERSAND_SEPARATED_STRING = "%s&%s&%s";

  /**
   * {@inheritDoc}
   */
  public function extract(OAuthRequest $request) {
    $this->checkPreconditions($request);
    $verb = URLUtils::percentEncode($request->getVerb());
    $url = URLUtils::percentEncode($request->getSanitizedUrl());
    $params = $this->getSortedAndEncodedParams($request);
    return sprintf(self::AMPERSAND_SEPARATED_STRING, $verb, $url, $params);
  }

  private function getSortedAndEncodedParams(OAuthRequest $request) {
    $params = array();
    MapUtils::decodeAndAppendEntries($request->getQueryStringParams(), $params);
    MapUtils::decodeAndAppendEntries($request->getBodyParams(), $params);
    MapUtils::decodeAndAppendEntries($request->getOauthParameters(), $params);
    $params = MapUtils::sort($params);
    return URLUtils::percentEncode(
        MapUtils::concatSortedPercentEncodedParams($params)
    );
  }

  private function checkPreconditions(OAuthRequest $request) {
    Preconditions::checkNotNull($request, "Cannot extract base string from null object");
    $oauthParameters = $request->getOauthParameters();
    if (!$oauthParameters) {
      throw new OAuthParametersMissingException($request);
    }
  }

}
