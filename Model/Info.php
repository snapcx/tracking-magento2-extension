<?php
namespace jframeworks\shippingtracking\Model;

use Magento\Sales\Model\Order\Shipment;

class Info extends \Magento\Shipping\Model\Info
{

    /**
     * @var \Magento\Sales\Model\Order\Shipment\TrackFactory
     */
    protected $salesOrderShipmentTrackFactory;
     
     /**
      * @var \Magento\Store\Model\StoreManagerInterface
      */
     
    protected $scopeConfig;

    public function __construct(
        \Magento\Shipping\Helper\Data $shippingData,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Api\ShipmentRepositoryInterface $shipmentRepository,
        \Magento\Shipping\Model\Order\TrackFactory $trackFactory,
        \Magento\Shipping\Model\ResourceModel\Order\Track\CollectionFactory $trackCollectionFactory,
        \Magento\Sales\Model\Order\Shipment\TrackFactory $salesOrderShipmentTrackFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($shippingData, $orderFactory, $shipmentRepository, $trackFactory, $trackCollectionFactory);
        $this->salesOrderShipmentTrackFactory = $salesOrderShipmentTrackFactory;
         $this->scopeConfig = $scopeConfig;
    }
    /**
     * Retrieve all tracking by order id
     *
     * @return array
     */
    public function getTrackingInfoByOrder()
    {
        
        $isEnable = $this
            ->scopeConfig
            ->getValue(
                  'shippingtracking/shippingtracking_settings/enable', 
                  \Magento\Store\Model\ScopeInterface::SCOPE_STORE
              );
        if ($isEnable == "1") {
            $shipTrack = [];
            $order = $this->_initOrder();
        
            if ($order) {
                $shipments = $order->getShipmentsCollection();
                foreach ($shipments as $shipment) {
                    $increment_id = $shipment->getIncrementId();
                    $tracks = $shipment->getTracksCollection();
                    $trackingInfos=[];
                    
                    foreach ($tracks as $track) {
                        $trackingInfos[$track->getId()]['tracking_number'] = $track->getTrackNumber();
                        $trackingInfos[$track->getId()]['title'] = $track->getTitle();
                        $trackingInfos[$track->getId()]['carrier_code'] = $track->getCarrierCode();
                    }
                    $shipTrack[$increment_id] = $trackingInfos;
                }
            }
            $this->_trackingInfo = $shipTrack;
            return $this->_trackingInfo;
        } else {
            return parent::getTrackingInfoByOrder();
        }
    }
    /**
     * Retrieve all tracking by ship id
     *
     * @return array
     */
    public function getTrackingInfoByShip()
    {
        
        $isEnable = $this
            ->scopeConfig->getValue(
                'shippingtracking/shippingtracking_settings/enable',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        if ($isEnable == "1") {
            $shipTrack = [];
            $shipment = $this->_initShipment();
            if ($shipment) {
                $increment_id = $shipment->getIncrementId();
                $tracks = $shipment->getTracksCollection();
                $trackingInfos=[];
                
                foreach ($tracks as $track) {
                    $trackingInfos[$track->getId()]['tracking_number'] = $track->getTrackNumber();
                    $trackingInfos[$track->getId()]['title'] = $track->getTitle();
                    $trackingInfos[$track->getId()]['carrier_code'] = $track->getCarrierCode();
                }
                $shipTrack[$increment_id] = $trackingInfos;
            }
            $this->_trackingInfo = $shipTrack;
            return $this->_trackingInfo;
        } else {
            return parent::getTrackingInfoByShip();
        }
    }
    /**
     * Retrieve tracking by tracking entity id
     *
     * @return array
     */
    public function getTrackingInfoByTrackId()
    {
        
        $isEnable = $this
            ->scopeConfig->getValue(
                'shippingtracking/shippingtracking_settings/enable', 
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        if ($isEnable == "1") {
            $track = $this->salesOrderShipmentTrackFactory->create()->load($this->getTrackId());
            $this->setShipId($track->getParentId());
            $shipment = $this->_initShipment();
            if ($shipment) {
                $increment_id = $shipment->getIncrementId();
                if ($track->getId() && $this->getProtectCode() == $track->getProtectCode()) {
                    $this->_trackingInfo[$increment_id][$track->getId()]['tracking_number'] = $track->getTrackNumber();
                    $this->_trackingInfo[$increment_id][$track->getId()]['title'] = $track->getTitle();
                    $this->_trackingInfo[$increment_id][$track->getId()]['carrier_code'] = $track->getCarrierCode();
                }
            }
            return $this->_trackingInfo;
        } else {
            return parent::getTrackingInfoByTrackId();
        }
    }
}
