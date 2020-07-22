<?php

namespace Pmclain\Stripe\Observer;

use Magento\Framework\Event\ObserverInterface;


class SimiAddStripePaymentMethod implements ObserverInterface {
	public $simiObjectManager;

	public function __construct(
		\Magento\Framework\ObjectManagerInterface $simiObjectManager
	) {
		$this->simiObjectManager = $simiObjectManager;
	}

	public function execute( \Magento\Framework\Event\Observer $observer ) {
		$obj = $observer->getObject();
		$obj->addPaymentMethod( 'pmclain_stripe', 1 );

		return;
	}
}