<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\Fixture;

use Magento\Mtf\Factory\Factory;

/**
 * Class PaypalPayflowProOrder
 * Guest checkout using PayPal Payflow Pro method
 *
 */
class PaypalPayflowProOrder extends OrderCheckout
{
    /**
     * Prepare data for guest checkout using Paypal Payflow Pro.
     */
    protected function _initData()
    {
        $this->checkoutFixture = Factory::getFixtureFactory()->getMagentoCheckoutGuestPayPalPayflowPro();
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '156.81',
            ],
        ];
    }
}
