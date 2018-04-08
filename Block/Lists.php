<?php
namespace Jframeworks\Shippingtracking\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;

class Lists extends \Magento\Shipping\Block\Tracking\Popup
{
    protected $helperApi;
   
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        DateTimeFormatterInterface $dateTimeFormatter,
        \Jframeworks\Shippingtracking\Helper\Api $helperApi,
        array $data = []
    ) {
        $this->helperApi = $helperApi;
        parent::__construct($context, $registry, $dateTimeFormatter, $data);
    }
    
   
   
    public function isEnabled()
    {
        return $this->_scopeConfig->getValue('shippingtracking/shippingtracking_settings/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
  
  /**
   *  jframeworks api call via curl
   *    Return array derived from json reponse from api
   *
   * @var $track_id string
   * @var $carrier_code string
   * @return array
   */
    public function getTrackingList($track_id, $carrier_code)
    {
        $responseData = $this->helperApi->getTrackingList($track_id, $carrier_code);

        $status = isset($responseData['status']) ? $responseData['status'] : null;

        if($status == 200 && isset($responseData['response'])) {
            return $responseData['response'];
        }

        if ($status == 403) {
            $response = "Please check your snapCX Subscription user key.";
        } else {
            $response = "There is no tracking available.";
        }
        return $response;
    }
}
