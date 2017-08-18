<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\Sales\Test\TestStep\SelectShippingMethodForOrderStep;
use Magento\GiftWrapping\Test\TestStep\SelectGiftWrappingForOrderStep;

/**
 * Assert Gift Wrapping Tax Totals for Order
 * - after adding GiftWrapping to the Order
 * - after selecting shipping method and removing GiftWrapping form the Order
 */
class AssertGiftWrappingTaxTotalsForOrder extends AbstractConstraint
{
    /**
     * Assert Gift Wrapping tax displayed in Order totals when selected
     * Assert Gift Wrapping tax not displayed in Order totals when not selected
     *
     * @param TestStepFactory $testStepFactory
     * @param OrderCreateIndex $orderCreateIndex
     * @param array $shipping
     * @param string $giftWrappingExclTaxDisplayText
     * @param string $giftWrappingInclTaxDisplayText
     * @return void
     */
    public function processAssert(
        TestStepFactory $testStepFactory,
        OrderCreateIndex $orderCreateIndex,
        array $shipping,
        $giftWrappingExclTaxDisplayText,
        $giftWrappingInclTaxDisplayText
    ) {
        $totals = $orderCreateIndex->getTotalsBlock()->getOrderTotals();
        $keys = array_keys($totals);

        \PHPUnit_Framework_Assert::assertContains(
            $giftWrappingExclTaxDisplayText,
            $keys,
            'There is no Gift Wrapping (Excl. Tax) in Order Totals block.'
        );

        \PHPUnit_Framework_Assert::assertContains(
            $giftWrappingInclTaxDisplayText,
            $keys,
            'There is no Gift Wrapping (Incl. Tax) in Order Totals block.'
        );

        // Select Shipping method
        $testStepFactory->create(
            SelectShippingMethodForOrderStep::class,
            ['orderCreateIndex' => $orderCreateIndex, 'shipping' => $shipping]
        )->run();

        // Remove GiftWrapping from the Order
        $testStepFactory->create(
            SelectGiftWrappingForOrderStep::class,
            ['orderCreateIndex' => $orderCreateIndex]
        )->run();

        $totals = $orderCreateIndex->getTotalsBlock()->getOrderTotals();
        $keys = array_keys($totals);

        \PHPUnit_Framework_Assert::assertNotContains(
            $giftWrappingExclTaxDisplayText,
            $keys,
            'Gift Wrapping (Excl. Tax) still appears in Order Totals block when none selected.'
        );

        \PHPUnit_Framework_Assert::assertNotContains(
            $giftWrappingInclTaxDisplayText,
            $keys,
            'Gift Wrapping (Incl. Tax) still appears in Order Totals block when none selected.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift wrap taxes are displayed only when gift wrap is selected';
    }
}
