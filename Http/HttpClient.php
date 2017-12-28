<?php

namespace jframeworks\shippingtracking\Http;

use Zend\Http\Client;

class HttpClient extends Client
{
    
    public static function create($url, $opts)
    {
        return new static($url, $opts);
    }
}
