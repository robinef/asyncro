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

class AuthTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test a simple Oauth call 
     */
    public function testSimpleHttpCall() {

        $callback = function (&$buffer) {
            json_decode($buffer);
        };
        $test = new Auth(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET);
        $test->getAccessToken(TOKEN_GATEWAY);
        $headers = ['Accept'=> OAUTH_VERSION_HEADER];
        try {
            $test->addAuthenticatedRequest(TEST_URL, $callback, 'GET',$headers);
             $this->assertTrue(TRUE);
        } catch(Excption $ex) {
             $this->assertTrue(FALSE);
        }
        $test->run();
        
    }
}

?>