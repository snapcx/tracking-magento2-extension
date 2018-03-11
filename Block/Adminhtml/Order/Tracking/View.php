<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Jframeworks\Shippingtracking\Block\Adminhtml\Order\Tracking;

class View extends \Magento\Shipping\Block\Adminhtml\Order\Tracking\View
{

    public function getDefaultCarrier()
    {
        return  $this->_scopeConfig->getValue(
            'shippingtracking/shippingtracking_settings/default_carrier',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
