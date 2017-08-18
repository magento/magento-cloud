<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\ConfigurableProduct\Test\Fixture\ConfigurableProduct;
use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after adding products by sku to order on backend, out of stock error message appears.
 */
class AssertProductsAddBySkuOnBackendIsOutOfStockFailMessage extends AbstractConstraint
{
    /**
     * Error message text.
     */
    const ERROR_MESSAGE = 'This product is out of stock.';

    /**
     * Assert that after adding products by sku to order on backend, out of stock error message appears.
     *
     * @param OrderCreateIndex $orderCreateIndex
     * @param array $products
     * @return void
     */
    public function processAssert(OrderCreateIndex $orderCreateIndex, array $products)
    {
        foreach ($products as $product) {
            if ($product instanceof ConfigurableProduct) {
                $createBlock = $orderCreateIndex->getCreateBlock();
                $createBlock->getItemsBlock()->getItemProductByName($product->getName())->configure();
                $orderCreateIndex->getConfigureProductBlock()->configProduct($product);
                $createBlock->getItemsBlock()->clickConfiguredToOrder();
                $createBlock->getTemplateBlock()->waitLoader();
            }
        }
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
        return 'Out of stock error message is present after adding products to order by sku on backend.';
    }
}
