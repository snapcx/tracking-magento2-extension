<?php

namespace Jframeworks\Shippingtracking\Plugin\Shipping\Block\Adminhtml\Order;

class TrackingPlugin
{
    protected $scopeConfig;
    protected $carrierFactory;
    protected $helperApi;

    public function __construct(
        \Magento\Shipping\Model\CarrierFactory $carrierFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Jframeworks\Shippingtracking\Helper\Api $helperApi
    ) {
        $this->carrierFactory = $carrierFactory;
        $this->scopeConfig = $context->getScopeConfig();
        $this->helperApi = $helperApi;
    }

    public function afterGetCarriers(\Magento\Shipping\Block\Adminhtml\Order\Tracking $subject, $result)
    {
        if ($this->getIsActive()) {
            $responseData = $this->helperApi->getCarriers();

            $result['custom'] = __('Custom Value');

            $response = isset($responseData['response']) ? $responseData['response'] : array();

            if((is_array($response) || is_object($response))) {
                foreach ($response as $key => $value) {
                    $result[$value->carrierCode] = $value->carrierName;

                    /* $result[] = array('value' => $value->carrierCode , 'label' => $value->carrierName); */
                }
            }
        }

        return $result;
    }
    public function getIsActive()
    {
        return ($this->scopeConfig->getValue('shippingtracking/shippingtracking_settings/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) == "1" );
    }
}