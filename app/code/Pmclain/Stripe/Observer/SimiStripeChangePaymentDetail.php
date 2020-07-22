<?php

namespace Pmclain\Stripe\Observer;

use Magento\Framework\Event\ObserverInterface;


class SimiStripeChangePaymentDetail implements ObserverInterface {
	public $simiObjectManager;
	protected $scopeConfig;

	public function __construct(
		\Magento\Framework\ObjectManagerInterface $simiObjectManager,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	) {
		$this->simiObjectManager = $simiObjectManager;
		$this->scopeConfig       = $scopeConfig;
	}

	public function execute( \Magento\Framework\Event\Observer $observer ) {
		$obj = $observer->getObject();

		if ( $obj->detail && strtolower( $obj->detail['payment_method'] ) == 'pmclain_stripe' ) {
			$test_mode                      = $this->getStoreConfig( 'payment/pmclain_stripe/test_mode' );
			$test_publishable_key           = $this->getStoreConfig( 'payment/pmclain_stripe/test_publishable_key' );
			$live_publishable_key           = $this->getStoreConfig( 'payment/pmclain_stripe/live_publishable_key' );
			$verify_3dsecure                = $this->getStoreConfig( 'payment/pmclain_stripe/verify_3dsecure' );
			$obj->detail['test_mode']       = $test_mode;
			$obj->detail['public_key']      = $test_mode ? $test_publishable_key : $live_publishable_key;
			$obj->detail['verify_3dsecure'] = $verify_3dsecure;
		}

		return $obj;
	}

	private function getStoreConfig( $path_config ) {
		$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

		return $this->scopeConfig->getValue( $path_config, $storeScope );
	}
}