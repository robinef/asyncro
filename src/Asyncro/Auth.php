<?php

namespace Asyncro;
/*
The MIT License (MIT)

Copyright (c) 2014 Frederic ROBINET

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
/*
 * @link http://github.com/robinef
 *
 * @package Asyncro
 * @author robinef@gmail.com
 */
/**
 * Asynchronous oauth client
 */
class Auth extends Client {

    /**
     *
     * @var string Oauth client Id 
     */
    var $_clientId = null;
    
    /**
     * Store access token for future requests
     * @var string 
     */
    var $_accessToken = null;
    
    /**
     *
     * @var string Oauth client secret
     */
    var $_clientSecret = null;
    
    /**
     * 
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct($clientId, $clientSecret) {
        $this->_clientId = $clientId;
        $this->_clientSecret = $clientSecret;
        parent::__construct();
    }
    
    /**
     * Get access token
     * @param string $tokenUrl
     */
    public function getAccessToken($tokenUrl) {
        
        $callback = function (&$response) {
            $data = json_decode($response, true);
            $this->_accessToken = $data['access_token'];
        };
        $this->addRequest($tokenUrl, $callback, "POST", ['Content-Type'=>'application/json'], json_encode([
					'client_id' => $this->_clientId,
					'client_secret' => $this->_clientSecret
					]));
        $this->run();
    }
    
    /**
     * Make an oauth authenticated request
     * @param string $url
     * @param function $callback
     * @param string $method
     * @param array[] $headers
     * @param string $body
     */
    public function addAuthenticatedRequest($url, $callback, $method = "GET", $headers = array(), $body = "") {
        //Add OAuth2 authorization header
        $headers['Authorization'] = 'OAuth oauth_token="'.$this->_accessToken.'", oauth_client_id="'.$this->_clientId.'"';
        $this->addRequest($url, $callback, $method, $headers, $body);
        
    }
    
}