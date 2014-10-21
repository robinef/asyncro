asyncro
=======

Asynchronous PHP client with OAuth 

## Install 

Copy past the json snippet in your composer.json file

``` json
{
    "repositories": [
        {
            "url": "https://github.com/robinef/asyncro.git",
            "type": "git"
        }        
    ],
    "require": {
        "robinef/asyncro":"dev-master"
    }
}
```

or 

``` bash

git clone https://github.com/robinef/asyncro.git

```

## Example

How to make multiple asynchronous call with OAuth : 

```
$client = new Asyncro\Auth(MY_OAUTH_ID, MY_OAUTH_SECRET);
$client->getAccessToken(MY_TOKEN_GATEWAY);
$callback = function (&$buffer) { echo $buffer; };
$headers = [];
// Add multiple call times with differents parameters
$client->addAuthenticatedRequest(MY_API_ENDPOINT, $callbackFunc, 'GET', $headers);
$client->run();
```

Everything will be run asynchronously and will enter callback function when finished

## CREDITS

Based on ReactPHP and Guzzle.


