<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\Fixture;

use Magento\Mtf\Factory\Factory;

/**
 * Class AuthorizeNetOrder
 * Guest checkout using Authorize.Net
 *
 */
class AuthorizeNetOrder extends OrderCheckout
{
    /**
     * Prepare data for guest checkout using Authorize.Net.
     */
    protected function _initData()
    {
        $this->checkoutFixture = Factory::getFixtureFactory()->getMagentoCheckoutGuestAuthorizenet();
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '156.81',
            ],
        ];
    }
}
