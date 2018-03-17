<?php

namespace Jframeworks\Shippingtracking\Plugin\Shipping\Block\Adminhtml\Order;

class TrackingPlugin
{
    protected $_scopeConfig;
    protected $_carrierFactory;
    protected $_helper;

    public function __construct(
        \Magento\Shipping\Model\CarrierFactory $carrierFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Jframeworks\Shippingtracking\Helper\Data $helper
    ) {
        $this->_carrierFactory = $carrierFactory;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_helper = $helper;
    }

    public function afterGetCarriers(\Magento\Shipping\Block\Adminhtml\Order\Tracking $subject, $result)
    {
        if ($this->getIsActive()) {
            $api_url ="https://api.snapcx.io/tracking/v1/getCarriers";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_helper->getHeaders());
            // Get response
            $response = curl_exec($curl);
            // Get HTTP status code
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close cURL
            curl_close($curl);

            $response = json_decode($response);

            $result = [];
            $result['custom'] = __('Custom Value');
            foreach ($response as $key => $value) {
                $result[$value->carrierCode] = $value->carrierName;

                /* $result[] = array('value' => $value->carrierCode , 'label' => $value->carrierName); */
            }
        }

        return $result;
    }
    public function getIsActive()
    {
        return ($this->_scopeConfig->getValue('shippingtracking/shippingtracking_settings/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) == "1" );
    }
}