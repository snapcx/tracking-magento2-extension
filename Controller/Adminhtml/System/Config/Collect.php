<?php
/**
 * Copyright © 2016 JFrameworks LLC. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Snapcx\Shippingtracking\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Collect extends Action
{
    protected $resultJsonFactory;
    protected $helper;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        \Jframeworks\Shippingtracking\Helper\Data $helper
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $api_url = "https://api.snapcx.io/tracking/v1/getCarriers";
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
        $result = $this->resultJsonFactory->create();
        return $result->setData($response);
    }
}
