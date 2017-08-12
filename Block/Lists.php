<?php
namespace Snapcx\Shippingtracking\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;

class Lists extends \Magento\Shipping\Block\Tracking\Popup
{
   
   
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        DateTimeFormatterInterface $dateTimeFormatter,
        array $data = []
    ) {
    
        parent::__construct($context, $registry, $dateTimeFormatter, $data);
    }
    
   
   
    public function isEnabled()
    {
        return $this->_scopeConfig->getValue('shippingtracking/shippingtracking_settings/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }//end function
  
    /**
     *  Get snapcx user_key set in config
     *
     * @return     string
     */
    public function getUserKey()
    {
            $api_key = $this->_scopeConfig->getValue('shippingtracking/shippingtracking_settings/shippingtracking_user_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $api_key;
    }   //end funcion
  
  /**
   *  Snapcx api call via curl
   *    Return array derived from json reponse from api
   *
   * @var $track_id string
   * @var $carrier_code string
   * @return array
   */
    public function getTrackingList($track_id, $carrier_code)
    {
        if ($carrier_code=='dhlint') {
            $carrier_code = 'dhl';
        }
        
        $base_url = $this->_scopeConfig->getValue('shippingtracking/shippingtracking_settings/snapcx_api_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        // URL to post to
        $base_url = str_replace('CARRIER_CODE', strtoupper($carrier_code), $base_url);
        $url = str_replace('TRACK_ID', $track_id, $base_url);
            // Start cURL
        $curl = curl_init();
    
        // Headers
        $headers = [];
        $headers[] = 'user_key:'.$this->getUserKey();
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);

        // Get response
        $response = curl_exec($curl);
    
        // Get HTTP status code
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // Close cURL
        curl_close($curl);
                
        // Return response from server
        if ($status==200 && $response!='') {
            $response = json_decode($response);
        } elseif ($status==403) {
            $response = "Please check your snapCX Subscription user key.";
        } else {
            $response = "There is no tracking available.";
        }
        return $response;
    }//end funcion
}
