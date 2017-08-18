<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after adding products by sku to order on backend.
 */
class AssertProductsAddBySkuOnBackendSuccessMessage extends AbstractConstraint
{
    /**
     * Assert that after adding products by sku to order on backend.
     *
     * @param OrderCreateIndex $orderCreateIndex
     * @param array $products
     * @return void
     */
    public function processAssert(OrderCreateIndex $orderCreateIndex, array $products)
    {
        $createBlock = $orderCreateIndex->getCreateBlock();
        foreach ($products as $product) {
            \PHPUnit_Framework_Assert::assertTrue(
                !empty($createBlock->getItemsBlock()->getProductsDataByFields(['name' => $product->getName()])),
                'Adding products by sku to order on backend is not successful.'
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Adding products by sku to order on backend is successful.';
    }
}
