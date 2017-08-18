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
 * requested quantity is more than allowed error message appears.
 */
class AssertProductsAddBySkuOnBackendMaxRequestedQtyFailMessage extends AbstractConstraint
{
    /**
     * Error message text.
     */
    const ERROR_MESSAGE = 'The most you may purchase is %d.';

    /**
     * Assert that after adding products by sku to order on backend,
     * requested quantity is more than allowed error message appears.
     *
     * @param OrderCreateIndex $orderCreateIndex
     * @param array $products
     * @param array $orderOptions
     * @return void
     */
    public function processAssert(OrderCreateIndex $orderCreateIndex, array $products, array $orderOptions)
    {
        foreach ($products as $key => $product) {
            $maxSaleQty = $product->getStockData()['max_sale_qty'];
            if ($orderOptions[$key]['qty'] > $maxSaleQty) {
                \PHPUnit_Framework_Assert::assertEquals(
                    $orderCreateIndex->getItemsOrderedMessagesBlock()->getErrorMessage(),
                    sprintf(self::ERROR_MESSAGE, $maxSaleQty),
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
        return 'Requested quantity is more than allowed error message is present after adding products to order.';
    }
}
