<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that requested qty does not meet specified increments error message is displayed after adding products
 * to order by sku on backend.
 */
class AssertProductsAddBySkuOnBackendQtyIncrementsFailMessage extends AbstractConstraint
{
    /**
     * Error message pattern.
     */
    const ERROR_MESSAGE = 'You can buy this product only in quantities of %d at a time.';

    /**
     * Assert that requested qty does not meet specified increments error message is displayed after adding products
     * to order by sku on backend.
     *
     * @param OrderCreateIndex $orderCreateIndex
     * @param array $products
     * @param array $orderOptions
     * @return void
     */
    public function processAssert(OrderCreateIndex $orderCreateIndex, array $products, array $orderOptions)
    {
        foreach ($products as $key => $product) {
            $qtyIncrements = $product->getStockData()['qty_increments'];
            if ($orderOptions[$key]['qty'] > $qtyIncrements) {
                \PHPUnit_Framework_Assert::assertEquals(
                    $orderCreateIndex->getItemsOrderedMessagesBlock()->getErrorMessage(),
                    sprintf(self::ERROR_MESSAGE, $qtyIncrements),
                    'Wrong error message is displayed.'
                );
            }
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Requested qty does not meet the increments error message is present after adding products to order.';
    }
}
