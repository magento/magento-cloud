<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after adding products by sku to order on backend, product is disabled error message appears.
 */
class AssertProductsAddBySkuOnBackendIsDisabledFailMessage extends AbstractConstraint
{
    /**
     * Error message text.
     */
    const ERROR_MESSAGE = 'This product is disabled.';

    /**
     * Assert that after adding products by sku to order on backend, product is disabled error message appears.
     *
     * @param OrderCreateIndex $orderCreateIndex
     * @return void
     */
    public function processAssert(OrderCreateIndex $orderCreateIndex)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            $orderCreateIndex->getItemsOrderedMessagesBlock()->getErrorMessage(),
            self::ERROR_MESSAGE,
            'Wrong error message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return '"This product is disabled" error message is present after adding products to order by sku on backend.';
    }
}
