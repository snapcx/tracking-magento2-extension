<?php

namespace Jframeworks\Shippingtracking\Plugin;

class ShippingModelOrderTrackPlugin
{
    protected $_scopeConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->_scopeConfig = $context->getScopeConfig();
    }

    public function afterGetNumberDetail(\Magento\Shipping\Model\Order\Track $subject, $result)
    {
        if ($this->getIsActive()) {
            $result = array(
                'tracking_number' => $subject->getTrackNumber(),
                'title' => $subject->getTitle(),
                'carrier_code' => $subject->getCarrierCode()
            );
        }

        return $result;
    }

    public function getIsActive()
    {
        return (
            $this->_scopeConfig->getValue('shippingtracking/shippingtracking_settings/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) == "1"
        );
    }
}
