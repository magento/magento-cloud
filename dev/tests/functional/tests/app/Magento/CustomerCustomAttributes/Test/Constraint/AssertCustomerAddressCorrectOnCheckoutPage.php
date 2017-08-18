<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Customer\Test\Fixture\Address;

/**
 * Assert that Custom Address Attributes are appeared on checkout page correctly
 */
class AssertCustomerAddressCorrectOnCheckoutPage extends AbstractConstraint
{
    /**
     * @param array           $products
     * @param Address         $address
     * @param CheckoutOnepage $checkoutOnepage
     */
    public function processAssert(array $products, Address $address, CheckoutOnepage $checkoutOnepage)
    {
        // create products
        $products = $this->objectManager->create(
            \Magento\Catalog\Test\TestStep\CreateProductsStep::class,
            ['products' => $products]
        )->run()['products'];

        // add products to cart
        $this->objectManager->create(
            \Magento\Checkout\Test\TestStep\AddProductsToTheCartStep::class,
            ['products' => $products]
        )->run();

        // go to checkout
        $this->objectManager->create(
            \Magento\Checkout\Test\TestStep\ProceedToCheckoutStep::class
        )->run();

        \PHPUnit_Framework_Assert::assertContains(
            $address->getCustomAttribute()['value'],
            $checkoutOnepage->getShippingBlock()->getSelectedAddress()
        );

        // add new shipping address
        $this->objectManager->create(
            \Magento\Checkout\Test\TestStep\AddNewShippingAddressStep::class,
            ['checkoutOnepage' => $checkoutOnepage, 'address' => $address]
        )->run();

        \PHPUnit_Framework_Assert::assertContains(
            $address->getCustomAttribute()['value'],
            $checkoutOnepage->getShippingBlock()->getSelectedAddress()
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Address Attribute is not displayed on One Page Checkout Shipping Address Page.';
    }
}
