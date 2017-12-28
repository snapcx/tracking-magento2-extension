<?php
namespace jframeworks\shippingtracking\Controller\Lists;

class Index extends \Magento\Framework\App\Action\Action
{
    
    private $registry;
    private $info;
    private $pageFactory;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Shipping\Model\Info $Info,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->registry = $registry;
        $this->info = $Info;
        $this->pageFactory = $pageFactory;
    }
  /**
   * List of the trcking information
   *
   * @var null
   * @return null
   */
    public function execute()
    {
        $shippingInfoModel = $this->info->loadByHash($this->getRequest()->getParam('hash'));
        $this->registry->register('current_shipping_info', $shippingInfoModel);
        if (count($shippingInfoModel->getTrackingInfo()) == 0) {
            return;
        }
        return $this->pageFactory->create();
    }
}
