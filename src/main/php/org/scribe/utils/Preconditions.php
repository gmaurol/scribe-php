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
 * Utils for checking preconditions and invariants
 * 
 * @author Mauro Gonzalez
 */
class Preconditions {
    const DEFAULT_MESSAGE = "Received an invalid parameter";
    const URL_PATTERN = "/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/";

    /**
     * Checks that an object is not null.
     * 
     * @param object any object
     * @param errorMsg error message
     * 
     * @throws IllegalArgumentException if the object is null
     */
    public static function checkNotNull($object, $errorMsg) {
        self::check($object !== null, $errorMsg);
    }

    /**
     * Checks that a string is not null or empty
     * 
     * @param string any string
     * @param errorMsg error message
     * 
     * @throws IllegalArgumentException if the string is null or empty
     */
    public static function checkEmptyString($string, $errorMsg) {
        self::check($string != null && !(trim($string) == ""), $errorMsg);
    }

    /**
     * Checks that a URL is valid
     * 
     * @param url any string
     * @param errorMsg error message
     */
    public static function checkValidUrl($url, $errorMsg) {
        self::checkEmptyString($url, $errorMsg);
        self::check(self::isUrl($url), $errorMsg);
    }

    /**
     * Checks that a URL is a valid OAuth callback
     *  
     * @param url any string
     * @param errorMsg error message
     */
    public static function checkValidOAuthCallback($url, $errorMsg) {
        self::checkEmptyString($url, $errorMsg);
        if (strtolower($url) !== OAuthConstants::OUT_OF_BAND) {
            self::check(self::isUrl($url), $errorMsg);
        }
    }

    private static function isUrl($url) {
        return preg_match(self::URL_PATTERN, $url);
    }

    private static function check($requirements, $error) {
        $message = ($error == null || strlen(trim($error)) <= 0) ? DEFAULT_MESSAGE : $error;
        if (!$requirements) {
            throw new IllegalArgumentException($message);
        }
    }

}
