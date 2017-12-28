<?php

/**
 * Copyright © 2016 Eecom . All rights reserved.
 */
namespace jframeworks\shippingtracking\Model\Rewrite\Config;

use Zend\Http\Client\Adapter\Curl;
use jframeworks\shippingtracking\Http\HttpClient;

class Carrier extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $api_url = 'https://api.snapcx.io/tracking/v1/getCarriers';
        $client = HttpClient::create($api_url, [
            'adapter' => Curl::class,
            'curloptions' => [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION=> true,
                CURLOPT_SSL_VERIFYPEER=> false,
                CURLOPT_HEADER=> false
            ]
        ]);
        
        // Get response
        $response = $client->send();
        // Get HTTP status code
        $status = $response->getStatusCode();
        $data = json_decode($response->getBody());
        $result = [];
        $result[] = [
            'value' => '0',
            'label' => 'Select Default Carrier'
        ];
        foreach ($data as $key => $value) {
            $result[] = [
                'value' => $value->carrierCode,
                'label' => $value->carrierName
            ];
        }
        return $result;
    }
}
