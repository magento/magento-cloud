<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\Fixture;

use Magento\Mtf\Factory\Factory;

/**
 * Class PaypalPaymentsProOrder
 * Guest checkout using PayPal Payments Pro method
 *
 */
class PaypalPaymentsProOrder extends OrderCheckout
{
    /**
     * Prepare data for guest checkout using Paypal Payments Pro.
     */
    protected function _initData()
    {
        $this->checkoutFixture = Factory::getFixtureFactory()->getMagentoCheckoutGuestPaypalDirect();
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '156.81',
            ],
        ];
    }
}
