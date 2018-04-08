<?php
namespace Jframeworks\Shippingtracking\Helper;

class Data extends \Magento\Shipping\Helper\Data
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    
     /**
      * @var \Magento\Framework\Url\EncoderInterface
      */
    protected $urlEncoder;
    
    protected $scopeConfig;
    
    protected $messageManager;

    protected $urlBuilder;

    protected $productMetadata;

    protected $moduleList;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\App\ProductMetadata $productMetadata,
        \Magento\Framework\Module\ModuleListInterface $moduleList
    ) {
        $this->storeManager = $storeManager;
        $this->urlEncoder = $urlEncoder;
        $this->scopeConfig = $scopeConfig;
        $this->messageManager = $messageManager;
        $this->urlBuilder = $urlBuilder;
        $this->productMetadata = $productMetadata;
        $this->moduleList = $moduleList;
    }
    /**
     * Retrieve tracking url with params
     *
     * @deprecated the non-model usage
     * @param  string $key
     * @param  integer|\Magento\Sales\Model\Order|\Magento\Sales\Model\Order\Shipment|\Magento\Sales\Model\Order\Shipment\Track $model
     * @param  string $method - option
     * @return string
     */
    protected function _getTrackingUrl($key, $model, $method = 'getId')
    {
        if (empty($model)) {
               $param = [$key => '']; // @deprecated after 1.4.0.0-alpha3
        } elseif (!is_object($model)) {
            $param = [$key => $model]; // @deprecated after 1.4.0.0-alpha3
        } else {
            $param = [
            'hash' => $this->urlEncoder->encode("{$key}:{$model->$method()}:{$model->getProtectCode()}")
            ];
        }

        $storeId = is_object($model) ? $model->getStoreId() : null;
        $storeModel = $this->storeManager->getStore($storeId);

        $url = $storeModel->getUrl('shippingtracking/lists/index', $param);
        $baseUrl = $this->urlBuilder->getBaseUrl();
        $url = $baseUrl . preg_replace('/(.*)shippingtracking/', 'shippingtracking', $url);

        return $url;
    }
        
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
        
    public function getMessage($message)
    {
        
        return $this->messageManager->addSuccess(__($message));
    }

    public function getMagentoVersion()
    {
        return $this->productMetadata->getVersion();
    }

    public function getExtensionVersion()
    {
        $moduleCode = 'Jframeworks_Shippingtracking';
        $moduleInfo = $this->moduleList->getOne($moduleCode);
        return $moduleInfo['setup_version'];
    }

    /**
     *  Get jframeworks user_key set in config
     *
     * @return     string
     */
    public function getUserKey()
    {
        return $this->getConfig(
            'shippingtracking/shippingtracking_settings/shippingtracking_user_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

}
