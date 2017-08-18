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
 * requested quantity is not available notice message appears.
 */
class AssertProductsAddBySkuOnBackendProductQtyIsNotEnough extends AbstractConstraint
{
    /**
     * Error message pattern.
     */
    const ERROR_MESSAGE = 'We don\'t have as many "%s" as you requested.';

    /**
     * Assert that after adding products by sku to order on backend,
     * requested quantity is not available notice message appears.
     *
     * @param OrderCreateIndex $orderCreateIndex
     * @param array $products
     * @param array $orderOptions
     * @return void
     */
    public function processAssert(OrderCreateIndex $orderCreateIndex, array $products, array $orderOptions)
    {
        foreach ($products as $key => $product) {
            if ($orderOptions[$key]['qty'] > $products[$key]->getQuantityAndStockStatus()['qty']) {
                \PHPUnit_Framework_Assert::assertEquals(
                    $orderCreateIndex->getItemsOrderedMessagesBlock()->getNoticeMessage(),
                    sprintf(self::ERROR_MESSAGE, $product->getName()),
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
        return 'Qty of products added to order on backend is not enough.';
    }
}
