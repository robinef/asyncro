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
 * Asynchronous http client
 */
class   Client {

    /**
     * Local http client
     */
    var $_client = null;
    
    /**
     *
     * @var \React\EventLoop\Factory  
     */
    var $_loop = null;
    
    /**
     * Ctor
     */
    public function __construct() {
        $this->_loop = \React\EventLoop\Factory::create();

        $dnsResolverFactory = new \React\Dns\Resolver\Factory();
        $dnsResolver = $dnsResolverFactory->createCached('8.8.8.8', $this->_loop);

        $factory = new \React\HttpClient\Factory();
        $this->_client = $factory->create($this->_loop, $dnsResolver);
    }
    
    /**
     * Add request to async request stack
     * @param type $url
     * @param type $callback
     */
    public function addRequest($url, $callback, $method = "GET", $headers = array(), $body = "") {
        
        if(strlen($body)> 0) {
            $headers['Content-Length'] = strlen($body);
        }
        $request = $this->_client->request($method, $url, $headers);
        $request->on('response', function ($response) use (&$callback) {

            $buffer = '';
            $response->on('data', function ($data) use (&$buffer) {
                $buffer .= $data;

            });
      
            $response->on('end', function() use (&$callback, &$buffer) {
                $callback($buffer);
            });
        });
        $request->on('end', function ($error, $response) {
            echo $error;
        });
        if(strlen($body)> 0) {
            $request->write($body);
        }
        $request->end();
    }
    
    /**
     * Run all the requests
     */
    public function run() {
        $this->_loop->run();
    }
}

