<?php
/**
 * Copyright © 2016 JFrameworks LLC. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Jframeworks\Shippingtracking\Model\Rewrite\Config;

class Carrier extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    protected $helperApi;

    public function __construct(
        \Jframeworks\Shippingtracking\Helper\Api $helperApi
    ) {
        $this->helperApi = $helperApi;
    }

    public function toOptionArray()
    {
        $responseData = $this->helperApi->getCarriers();

        $result[] = ['value'=>'0','label'=>'Select Default Carrier'];
        $response = isset($responseData['response']) ? $responseData['response'] : array();

        if(is_array($response) || is_object($response)) {
            foreach ($response as $key => $value) {
                $result[] = ['value' => $value->carrierCode , 'label' => $value->carrierName];
            }
        }

        return $result;
    }
}
