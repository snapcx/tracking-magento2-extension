<?php
namespace jframeworks\shippingtracking\Block\Order;

use Magento\Framework\View\Element\Template;

class View extends \Magento\Sales\Block\Order\View
{

      
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Payment\Helper\Data $paymentHelper,
        array $data = []
    ) {
        parent::__construct($context, $registry, $httpContext, $paymentHelper, $data);
    }
        
    
    
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
        return $this->_scopeConfig->getValue('shippingtracking/shippingtracking_settings/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }//end function
}
