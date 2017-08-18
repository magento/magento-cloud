<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after adding products by sku to order on backend, product requires attention error message appears.
 */
class AssertProductsAddBySkuOnBackendRequiredAttentionFailMessage extends AbstractConstraint
{
    /**
     * Error message pattern.
     */
    const ERROR_MESSAGE = '%d product(s) require attention.';

    /**
     * Assert that after adding products by sku to order on backend, product requires attention error message appears.
     *
     * @param OrderCreateIndex $orderCreateIndex
     * @param array $products
     * @return void
     */
    public function processAssert(OrderCreateIndex $orderCreateIndex, array $products)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            $orderCreateIndex->getOrderErrorMessagesBlock()->getErrorMessage(),
            sprintf(self::ERROR_MESSAGE, count($products)),
            'Product requires attention notice message is not displayed.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Product requires attention error message is present.';
    }
}
