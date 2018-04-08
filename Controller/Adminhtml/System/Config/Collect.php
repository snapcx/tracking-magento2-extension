<?php
/**
 * Copyright © 2016 JFrameworks LLC. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Jframeworks\Shippingtracking\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Collect extends Action
{
    protected $resultJsonFactory;
    protected $helperApi;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        \Jframeworks\Shippingtracking\Helper\Api $helperApi
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helperApi = $helperApi;
        parent::__construct($context);
    }

    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $responseData = $this->helperApi->getCarriers();
        $result = $this->resultJsonFactory->create();

        if(isset($responseData['response'])) {
            $result->setData($responseData['response']);
        }

        return $result;
    }
}
