<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerAddressAttribute;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after customer address attribute error require message appears on checkout shipping address page.
 */
class AssertCustomerAddressAttributeErrorRequireMessage extends AbstractConstraint
{
    /**
     * Text of error require message.
     */
    const ERROR_REQUIRE_MESSAGE = 'This is a required field.';

    /**
     * Assert that after customer address attribute error require message appears on checkout shipping address page.
     *
     * @param CustomerAddressAttribute $customAttribute
     * @param CheckoutOnepage $checkoutOnepage
     */
    public function processAssert(CustomerAddressAttribute $customAttribute, CheckoutOnepage $checkoutOnepage)
    {
        $errorMessages = $checkoutOnepage->getShippingBlock()->getAddressModalBlock()->getErrorMessages();
        \PHPUnit_Framework_Assert::assertEquals(
            self::ERROR_REQUIRE_MESSAGE,
            $errorMessages[$customAttribute->getFrontendLabel()]
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Address Attribute error required message appears on One Page Checkout Shipping Address Page.';
    }
}
