<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Customer\Test\Fixture\Customer;

/**
 * Class AssertGiftWrappingNotOnOrderCreationPage
 * Assert that deleted Gift Wrapping can not be found on order creation page in backend.
 */
class AssertGiftWrappingNotOnOrderCreationPage extends AbstractConstraint
{
    /**
     * Assert that deleted Gift Wrapping can not be found on order creation page in backend.
     *
     * @param OrderIndex $orderIndex
     * @param OrderCreateIndex $orderCreateIndex
     * @param GiftWrapping $giftWrapping
     * @param Customer $customer
     * @return void
     */
    public function processAssert(
        OrderIndex $orderIndex,
        OrderCreateIndex $orderCreateIndex,
        GiftWrapping $giftWrapping,
        Customer $customer
    ) {
        $orderIndex->open();
        $orderIndex->getGridPageActions()->addNew();
        $orderCreateIndex->getCustomerBlock()->selectCustomer($customer);
        if ($orderCreateIndex->getStoreBlock()->isVisible()) {
            $orderCreateIndex->getStoreBlock()->selectStoreView();
        }
        \PHPUnit_Framework_Assert::assertFalse(
            $orderCreateIndex->getGiftOptionsBlock()->isGiftWrappingAvailable($giftWrapping->getDesign()),
            'Gift Wrapping \'' . $giftWrapping->getDesign() . '\' is present on order creation page.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping can not be found on order creation page in backend.';
    }
}
