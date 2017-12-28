<?php
namespace jframeworks\shippingtracking\Block;

use Zend\Http\Client\Adapter\Curl;
use jframeworks\shippingtracking\Http\HttpClient;

class Lists extends \Magento\Shipping\Block\Tracking\Popup
{
    public function isEnabled()
    {
        return $this
            ->_scopeConfig
            ->getValue(
                'shippingtracking/shippingtracking_settings/enable',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
    }//end function
    /**
     *  Get jframeworks user_key set in config
     *
     * @return     string
     */
    public function getUserKey()
    {
            $api_key = $this
                ->_scopeConfig
                ->getValue(
                    'shippingtracking/shippingtracking_settings/shippingtracking_user_key',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
            return $api_key;
    }   //end funcion
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
        if ($carrier_code=='dhlint') {
            $carrier_code = 'dhl';
        }
        $base_url = $this
            ->_scopeConfig
            ->getValue(
                'shippingtracking/shippingtracking_settings/jframeworks_api_url',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        // URL to post to
        $base_url = str_replace('CARRIER_CODE', strtoupper($carrier_code), $base_url);
        $url = str_replace('TRACK_ID', $track_id, $base_url);
        // Headers
        $headers = [];
        $headers[] = 'user_key:'.$this->getUserKey();
        
        $client = HttpClient::create($url, [
            'adapter' => Curl::class,
            'curloptions' => [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION=> true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_VERIFYPEER=> false,
                CURLOPT_HEADER=> false
            ]
        ]);
        
        $response = $client->send();
        // Get HTTP status code
        $status = $response->getStatusCode();
        // Return response from server
        $data = $response->getBody();
        if ($status == 200 && $data!='') {
            $data = json_decode($data);
        } elseif ($status==403) {
            $data = "Please check your snapCX Subscription user key.";
        } else {
            $data= "There is no tracking available.";
        }
        return $data;
    }//end funcion
}
