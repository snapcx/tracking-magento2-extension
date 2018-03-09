<?php

namespace Jframeworks\Shippingtracking\Plugin;

class ShippingModelOrderTrackPlugin
{
    public function afterGetNumberDetail(\Magento\Shipping\Model\Order\Track $subject, $result)
    {
        return array(
            'tracking_number' => $subject->getTrackNumber(),
            'title' => $subject->getTitle(),
            'carrier_code' => $subject->getCarrierCode()
        );
    }
}
