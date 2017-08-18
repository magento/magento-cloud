<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertRemoveStoreCreditSuccessMessage
 * Assert that after remove store credit successful message appears
 */
class AssertRemoveStoreCreditSuccessMessage extends AbstractConstraint
{
    /**
     * Message displayed after remove store credit
     */
    const SUCCESS_REMOVE_MESSAGE = 'The store credit payment has been removed from shopping cart.';

    /**
     * Assert that after remove store credit successful message appears
     *
     * @param CheckoutCart $checkoutCart
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_REMOVE_MESSAGE,
            $checkoutCart->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Store credit success remove message is present.';
    }
}
