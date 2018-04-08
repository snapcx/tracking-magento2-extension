<?php
namespace Jframeworks\Shippingtracking\Controller\Lists;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $registry;
    protected $info;
    protected $pageFactory;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Shipping\Model\Info $info,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->registry = $registry;
        $this->info = $info;
        $this->pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    /**
     * List of the tracking information
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
