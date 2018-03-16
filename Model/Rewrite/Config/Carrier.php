<?php
/**
 * Copyright © 2016 JFrameworks LLC. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Jframeworks\Shippingtracking\Model\Rewrite\Config;

class Carrier extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    protected $helper;

    public function __construct(
        \Jframeworks\Shippingtracking\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    public function toOptionArray()
    {
        $api_url ="https://api.snapcx.io/tracking/v1/getCarriers";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->helper->getHeaders());
        // Get response
        $response = curl_exec($curl);
        // Get HTTP status code
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // Close cURL
        curl_close($curl);
        
        $response = json_decode($response);
    
        $result = [];
        $result[] = ['value'=>'0','label'=>'Select Default Carrier'];
        foreach ($response as $key => $value) {
            $result[] = ['value' => $value->carrierCode , 'label' => $value->carrierName];
        }

            return $result;
    }
}
