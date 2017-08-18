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
class AssertProductsAddBySkuOnBackendSpecifyProductOptionsLink extends AbstractConstraint
{
    /**
     * Error message text.
     */
    const ERROR_MESSAGE = 'You need to choose options for your item.';

    /**
     * Assert that after adding products by sku to order on backend,
     * requested quantity is not available notice message appears.
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
                'Message is not present after adding products to order.'
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
        return '"You need to choose options for your item" message is present';
    }
}
