<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after adding products by sku to order on backend,
 * requested quantity is less than allowed error message appears.
 */
class AssertProductsAddBySkuOnBackendMinRequestedQtyFailMessage extends AbstractConstraint
{
    /**
     * Error message pattern.
     */
    const ERROR_MESSAGE = 'The fewest you may purchase is %d.';

    /**
     * Assert that after adding products by sku to order on backend,
     * requested quantity is less than allowed error message appears.
     *
     * @param OrderCreateIndex $orderCreateIndex
     * @param array $products
     * @param array $orderOptions
     * @return void
     */
    public function processAssert(OrderCreateIndex $orderCreateIndex, array $products, array $orderOptions)
    {
        foreach ($products as $key => $product) {
            $minSaleQty = $product->getStockData()['min_sale_qty'];
            if ($orderOptions[$key]['qty'] < $minSaleQty) {
                \PHPUnit_Framework_Assert::assertEquals(
                    $orderCreateIndex->getItemsOrderedMessagesBlock()->getErrorMessage(),
                    sprintf(self::ERROR_MESSAGE, $minSaleQty),
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
        return 'Requested quantity is less than allowed error message is present after adding products to order.';
    }
}
