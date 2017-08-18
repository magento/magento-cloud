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
 * error message 'Enter an actual number in the Qty field' appears.
 */
class AssertProductsAddBySkuOnBackendActualNumberInQtyFieldNoticeMessage extends AbstractConstraint
{
    /**
     * Notice message.
     */
    const NOTICE_MESSAGE = 'Please enter an actual number in the "Qty" field.';

    /**
     * Assert that after adding products by sku to order on backend,
     * error message 'Enter an actual number in the Qty field' appears.
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
                self::NOTICE_MESSAGE,
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
        return 'Enter an actual number in Qty field error message displayed after adding products by sku on backend.';
    }
}
