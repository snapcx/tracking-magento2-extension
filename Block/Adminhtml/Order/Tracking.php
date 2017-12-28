<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace jframeworks\shippingtracking\Block\Adminhtml\Order;

use Magento\Backend\Block\Template;
use Zend\Http\Client\Adapter\Curl;
use jframeworks\shippingtracking\Http\HttpClient;

/**
 * Shipment tracking control form
 */

class Tracking extends \Magento\Shipping\Block\Adminhtml\Order\Tracking
{
    private $scopeConfig;
    private $carrierFactory;
    public function __construct(
        \Magento\Shipping\Model\CarrierFactory $carrierFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->carrierFactory = $carrierFactory;
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
        // Get Data
            $data = json_decode($response->getBody());
            $result = [];
            $result['custom'] = __('Custom Value');
            foreach ($data as $key => $value) {
                $result[$value->carrierCode] = $value->carrierName;
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
        return $this
            ->_scopeConfig
            ->getValue(
                'shippingtracking/shippingtracking_settings/enable',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
    }
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
