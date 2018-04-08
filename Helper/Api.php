<?php
namespace Jframeworks\Shippingtracking\Helper;

class Api
{
    public $curl;
    public $helper;

    public function __construct(
        \Magento\Framework\HTTP\Adapter\Curl $curl,
        \Jframeworks\Shippingtracking\Helper\Data $helper
    ) {
        $this->curl = $curl;
        $this->helper = $helper;
    }

    public function getCurlHeaders()
    {
        return array(
            'user_key: '.$this->helper->getUserKey(),
            'platform: magento',
            'version: '.$this->helper->getMagentoVersion(),
            'pVersion: '.$this->helper->getExtensionVersion()
        );
    }

    /**
     * Get list of Carriers
     *
     * @return array [ "status" => (int), "response" => (array) ]
     */
    public function getCarriers()
    {
        $apiUrl ="https://api.snapcx.io/tracking/v1/getCarriers";

        return $this->sendCurlRequest($apiUrl);
    }

    /**
     *  Get tracking details
     *
     * @var $trackId string
     * @var $carrierCode string
     * @return array [ "status" => (int), "response" => (array) ]
     */
    public function getTrackingList($trackId, $carrierCode)
    {
        if ($carrierCode=='dhlint') {
            $carrierCode = 'dhl';
        }

        $apiUrl = $this->helper->getConfig('shippingtracking/shippingtracking_settings/jframeworks_api_url');
        // URL to post to
        $apiUrl = str_replace(
            array('CARRIER_CODE', 'TRACK_ID'),
            array(strtoupper($carrierCode), $trackId),
            $apiUrl
        );

        return $this->sendCurlRequest($apiUrl);
    }

    public function sendCurlRequest($url)
    {
        // Start cURL
        $this->curl->setOptions(array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ));
        $this->curl->setConfig(array(
            'header' => false
        ));
        // Get response
        $this->curl->write('GET', $url, '1.1', $this->getCurlHeaders());
        $response = $this->curl->read();
        $status = $this->curl->getInfo(CURLINFO_HTTP_CODE);

        // Close cURL
        $this->curl->close();

        $result = array(
            "status" => $status,
            "response" => json_decode($response)
        );

        return $result;
    }
}