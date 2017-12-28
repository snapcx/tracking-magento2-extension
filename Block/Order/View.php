<?php
namespace jframeworks\shippingtracking\Block\Order;

class View extends \Magento\Sales\Block\Order\View
{
    protected function _construct()
    {
        parent::_construct();
        $isEnabled = $this->_scopeConfig->getValue(
            'shippingtracking/shippingtracking_settings/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($isEnabled=="1") {
            $this->setTemplate('shippingtracking/order/view.phtml');
        } else {
             $this->setTemplate('Magento_Sales::order/view.phtml');
        }
    }
    /**
     *  Check if uctracker is active
     *
     * @return     string
     */
    public function isActive()
    {
        return $this
            ->_scopeConfig
        ->getValue(
            'shippingtracking/shippingtracking_settings/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }//end function
}
