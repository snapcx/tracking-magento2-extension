<?php
namespace jframeworks\shippingtracking\Controller\Lists;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $registry;
    
    protected $_Info;
    
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Shipping\Model\Info $Info,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->registry = $registry;
        $this->_Info = $Info;
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

  /**
   * List of the trcking information
   *
   * @var null
   * @return null
   */
    public function execute()
    {
    
        $shippingInfoModel = $this->_Info->loadByHash($this->getRequest()->getParam('hash'));
        $this->registry->register('current_shipping_info', $shippingInfoModel);
        if (count($shippingInfoModel->getTrackingInfo()) == 0) {
            return;
        }
        return $this->_pageFactory->create();
    }
}
