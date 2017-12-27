<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace jframeworks\shippingtracking\Block\Adminhtml\Order;

use Magento\Backend\Block\Template;

/**
 * Shipment tracking control form
 */
 
class Tracking extends \Magento\Shipping\Block\Adminhtml\Order\Tracking
{

 protected $scopeConfig;

    protected $_carrierFactory;
   
    public function __construct(
        \Magento\Shipping\Model\CarrierFactory $carrierFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_carrierFactory = $carrierFactory;
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context, $shippingConfig, $registry, $data);
    }
  
    protected function _prepareLayout()
    {
        $this->addChild(
            'add_button',
            'Magento\Backend\Block\Widget\Button',
            ['label' => __('Add Tracking Number'), 'class' => '', 'onclick' => 'trackingControl.add()']
        );
    }
    
    public function getShipment()
    {
        return $this->_coreRegistry->registry('current_shipment');
    }
  
    public function getCarriers()
    {
        
        $isEnabled = $this->scopeConfig->getValue(
            'shippingtracking/shippingtracking_settings/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($isEnabled=="1") {
            $api_url ="https://api.snapcx.io/tracking/v1/getCarriers";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
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

            return $result;
        } else {
            $carriers = [];
            $carrierInstances = $this->_getCarriersInstances();
            $carriers['custom'] = __('Custom Value');
            foreach ($carrierInstances as $code => $carrier) {
                if ($carrier->isTrackingAvailable()) {
                    $carriers[$code] = $carrier->getConfigData('title');
                }
            }
        }
        return $carriers;
    }
    public function isActive()
    {
        return $this->_scopeConfig->getValue('shippingtracking/shippingtracking_settings/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }//end function
    
    protected function _getCarriersInstances()
    {
        return $this->_shippingConfig->getAllCarriers($this->getShipment()->getStoreId());
    }
    
    public function getDefaultCarrier()
    {
        
        return  $this->scopeConfig->getValue(
            'shippingtracking/shippingtracking_settings/default_carrier',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
