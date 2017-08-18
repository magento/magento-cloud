<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after adding products by sku to order on backend, sku not found error message appears.
 */
class AssertProductsAddBySkuOnBackendSkuNotFound extends AbstractConstraint
{
    /**
     * Error message text.
     */
    const ERROR_MESSAGE = 'The SKU was not found in the catalog.';

    /**
     * Assert that after adding products by sku to order on backend, sku not found error message appears.
     *
     * @param OrderCreateIndex $orderCreateIndex
     * @return void
     */
    public function processAssert(OrderCreateIndex $orderCreateIndex)
    {
        $noticeMessages = $orderCreateIndex->getOrderErrorMessagesBlock()->getNoticeMessages();
        foreach ($noticeMessages as $message) {
            \PHPUnit_Framework_Assert::assertEquals(
                $message,
                self::ERROR_MESSAGE,
                'Wrong error message is displayed.'
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
        return 'Sku not found error message is present after adding products by sku to order on backend.';
    }
}
