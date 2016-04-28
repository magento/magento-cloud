<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Fixture;

use Magento\Mtf\ObjectManager;

/**
 * Fixture for registered customer checkout
 */
class RegisteredPaypalExpress extends GuestPaypalExpress
{
    /**
     * Get configuration fixtures
     *
     * @return array
     */
    protected function _getConfigFixtures()
    {
        $list = parent::_getConfigFixtures();
        $list[] = 'address_template';
        return $list;
    }

    /**
     * Get billing address for checkout
     *
     * @return \Magento\Customer\Test\Fixture\Address
     */
    protected function _initBillingAddress()
    {
        $billing = ObjectManager::getInstance()->create(
            '\Magento\Customer\Test\Fixture\Address',
            ['dataset' => parent::_initBillingAddress()]
        );
        return $billing;
    }
}
