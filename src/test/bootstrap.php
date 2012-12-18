<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define ('SCRIBE_ROOT', realpath(dirname(__FILE__) . '/../main/php/org/scribe'));

require_once SCRIBE_ROOT . '/builder/ServiceBuilder.php';
require_once SCRIBE_ROOT . '/builder/api/Api.php';
require_once SCRIBE_ROOT . '/builder/api/DefaultApi10a.php';
require_once SCRIBE_ROOT . '/builder/api/DefaultApi20.php';
require_once SCRIBE_ROOT . '/builder/api/DropBoxApi.php';   
require_once SCRIBE_ROOT . '/builder/api/FacebookApi.php';     
require_once SCRIBE_ROOT . '/builder/api/FoursquareApi.php';  
require_once SCRIBE_ROOT . '/builder/api/LinkedInApi.php'; 
require_once SCRIBE_ROOT . '/builder/api/FlickrApi.php';
require_once SCRIBE_ROOT . '/builder/api/LoveFilmApi.php';       
require_once SCRIBE_ROOT . '/builder/api/PlurkApi.php';      
require_once SCRIBE_ROOT . '/builder/api/SohuWeiboApi.php';  
require_once SCRIBE_ROOT . '/builder/api/VimeoApi.php';      
require_once SCRIBE_ROOT . '/builder/api/YahooApi.php';
require_once SCRIBE_ROOT . '/builder/api/ConstantContactApi.php';  
require_once SCRIBE_ROOT . '/builder/api/EvernoteApi.php';  
require_once SCRIBE_ROOT . '/builder/api/Foursquare2Api.php';  
require_once SCRIBE_ROOT . '/builder/api/GoogleApi.php';      
require_once SCRIBE_ROOT . '/builder/api/LiveApi.php';      
require_once SCRIBE_ROOT . '/builder/api/NeteaseWeibooApi.php';  
require_once SCRIBE_ROOT . '/builder/api/SinaWeiboApi.php';  
require_once SCRIBE_ROOT . '/builder/api/TwitterApi.php';    
require_once SCRIBE_ROOT . '/builder/api/VkontakteApi.php';  
require_once SCRIBE_ROOT . '/builder/api/YammerApi.php';


require_once SCRIBE_ROOT . '/exceptions/IllegalArgumentException.php';
require_once SCRIBE_ROOT . '/exceptions/OAuthException.php';
require_once SCRIBE_ROOT . '/exceptions/OAuthParametersMissingException.php';
require_once SCRIBE_ROOT . '/exceptions/OAuthSignatureException.php';
require_once SCRIBE_ROOT . '/exceptions/UnsupportedOperationException.php';

require_once SCRIBE_ROOT . '/extractors/BaseStringExtractor.php';
require_once SCRIBE_ROOT . '/extractors/HeaderExtractor.php';
require_once SCRIBE_ROOT . '/extractors/RequestTokenExtractor.php';
require_once SCRIBE_ROOT . '/extractors/AccessTokenExtractor.php';

require_once SCRIBE_ROOT . '/extractors/BaseStringExtractorImpl.php';
require_once SCRIBE_ROOT . '/extractors/HeaderExtractorImpl.php';
require_once SCRIBE_ROOT . '/extractors/JsonTokenExtractor.php';
require_once SCRIBE_ROOT . '/extractors/RequestTokenExtractor.php';
require_once SCRIBE_ROOT . '/extractors/TokenExtractorImpl.php';
require_once SCRIBE_ROOT . '/extractors/TokenExtractor20Impl.php';

require_once SCRIBE_ROOT . '/model/Verb.php';
require_once SCRIBE_ROOT . '/model/SignatureType.php';
require_once SCRIBE_ROOT . '/model/URL.php';
require_once SCRIBE_ROOT . '/model/OAuthConfig.php';
require_once SCRIBE_ROOT . '/model/OAuthConstants.php';
require_once SCRIBE_ROOT . '/model/Token.php';
require_once SCRIBE_ROOT . '/model/Request.php';
require_once SCRIBE_ROOT . '/model/Response.php';
require_once SCRIBE_ROOT . '/model/Verifier.php';
require_once SCRIBE_ROOT . '/model/OAuthRequest.php';
require_once SCRIBE_ROOT . '/model/HttpURLConnection.php';
require_once SCRIBE_ROOT . '/model/HttpURLConnectionCurl.php';

require_once SCRIBE_ROOT . '/services/SignatureService.php';
require_once SCRIBE_ROOT . '/services/HMACSha1SignatureService.php';
require_once SCRIBE_ROOT . '/services/PlaintextSignatureService.php';
require_once SCRIBE_ROOT . '/services/TimestampService.php';
require_once SCRIBE_ROOT . '/services/TimestampServiceImpl.php';

require_once SCRIBE_ROOT . '/oauth/OAuthService.php';
require_once SCRIBE_ROOT . '/oauth/OAuth10aServiceImpl.php';
require_once SCRIBE_ROOT . '/oauth/OAuth20ServiceImpl.php';

require_once SCRIBE_ROOT . '/utils/URLUtils.php';
require_once SCRIBE_ROOT . '/utils/MapUtils.php';
require_once SCRIBE_ROOT . '/utils/Preconditions.php';