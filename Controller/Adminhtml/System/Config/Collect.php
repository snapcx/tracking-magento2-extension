<?php
/**
 * Copyright © 2016 MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Snapcx\Shippingtracking\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Zend\Http\Client\Adapter\Curl;
use jframeworks\shippingtracking\Http\HttpClient;

class Collect extends Action
{
    
    private $resultJsonFactory;
    
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $api_url ="https://api.snapcx.io/tracking/v1/getCarriers";
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
        $result = $this->resultJsonFactory->create();
        return $result->setData($data);
    }
}
