<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Customer\Test\Fixture\Address;
use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\TestStep\TestStepFactory;

/**
 * Assert that Gift Wrapping for Order can be found during one page checkout on frontend.
 */
class AssertGiftWrappingForOrderOnFrontendCheckout extends AbstractConstraint
{
    /**
     * Assert that Gift Wrapping for Items can be found on Checkout Payment Totals block.
     *
     * @param TestStepFactory $stepFactory
     * @param CheckoutCart $checkoutCart
     * @param CheckoutOnepage $checkoutOnepage
     * @param GiftWrapping $giftWrapping
     * @param Address $shippingAddress
     * @param string $giftWrappingTotal
     * @param array $shipping
     * @return void
     */
    public function processAssert(
        TestStepFactory $stepFactory,
        CheckoutCart $checkoutCart,
        CheckoutOnepage $checkoutOnepage,
        GiftWrapping $giftWrapping,
        Address $shippingAddress,
        $giftWrappingTotal,
        array $shipping
    ) {
        $checkoutCart->open();
        $checkoutCart->getGiftOptionsOrderBlock()->selectGiftWrapping($giftWrapping);

        $stepFactory->create(
            \Magento\Checkout\Test\TestStep\ProceedToCheckoutStep::class
        )->run();
        $stepFactory->create(
            \Magento\Checkout\Test\TestStep\FillShippingAddressStep::class,
            ['shippingAddress' => $shippingAddress]
        )->run();
        $stepFactory->create(
            \Magento\Checkout\Test\TestStep\FillShippingMethodStep::class,
            ['shipping' => $shipping]
        )->run();

        \PHPUnit_Framework_Assert::assertEquals(
            $giftWrappingTotal,
            $checkoutOnepage->getGiftWrappingTotalsBlock()->getGiftWrappingAmount(),
            'There is no Order Wrapping on Onepage checkout Totals.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping for Order is present on Checkout Payment Totals block.';
    }
}
