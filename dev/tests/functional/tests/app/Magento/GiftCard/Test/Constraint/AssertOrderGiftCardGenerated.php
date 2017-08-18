<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Constraint;

use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\Sales\Test\Page\Adminhtml\SalesOrderView;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that the gifcard is generated on the sales info page
 */
class AssertOrderGiftCardGenerated extends AbstractConstraint
{
    /**
     * Assert that giftcard is generated in the order page in admin panel
     *
     * @param string $orderId
     * @param OrderIndex $salesOrder
     * @param SalesOrderView $salesOrderView
     * @return void
     */
    public function processAssert(
        $orderId,
        OrderIndex $salesOrder,
        SalesOrderView $salesOrderView
    ) {
        $salesOrder->open();
        $salesOrder->getSalesOrderGrid()->searchAndOpen(['id' => $orderId]);

        $productOptions = $salesOrderView->getInformationBlock()->getProductOptions('sku_test_product_giftcard');

        $generated = false;
        if (isset($productOptions['Gift Card Accounts:'])) {
            $generated = !empty($productOptions['Gift Card Accounts:'])
                && $productOptions['Gift Card Accounts:'] != 'N/A';
        }
        \PHPUnit_Framework_Assert::assertTrue(
            $generated
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift card present on order info tab.';
    }
}
