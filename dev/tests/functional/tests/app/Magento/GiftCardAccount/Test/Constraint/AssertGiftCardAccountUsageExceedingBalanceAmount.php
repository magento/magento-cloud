<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Checkout\Test\TestStep\AddProductsToTheCartStep;
use Magento\Checkout\Test\TestStep\FillShippingAddressStep;
use Magento\Checkout\Test\TestStep\FillShippingMethodStep;
use Magento\Checkout\Test\TestStep\PlaceOrderStep;
use Magento\Checkout\Test\TestStep\ProceedToCheckoutStep;
use Magento\Checkout\Test\TestStep\SelectPaymentMethodStep;
use Magento\Checkout\Test\TestStep\ViewAndEditCartStep;
use Magento\Customer\Test\Fixture\Address;
use Magento\Customer\Test\Fixture\Customer;
use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\GiftCardAccount\Test\TestStep\ApplyGiftCardStep;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepFactory;

/**
 * Assert that gift card cannot be used twice exceeding balance amount.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AssertGiftCardAccountUsageExceedingBalanceAmount extends AbstractAssertGiftCardAccountOnFrontend
{
    /**
     * Onepage checkout page.
     *
     * @var CheckoutOnepage
     */
    private $checkoutOnepage;

    /**
     * Test step factory.
     *
     * @var TestStepFactory
     */
    private $testStepFactory;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Assert that gift card cannot be used twice exceeding balance amount.
     *
     * @param CheckoutOnepage $checkoutOnepage
     * @param TestStepFactory $testStepFactory
     * @param FixtureFactory $fixtureFactory
     * @param GiftCardAccount $giftCardAccount
     * @param Customer $customer
     * @param Address $shippingAddress
     * @param CatalogProductSimple $product
     * @param array $shipping
     * @param array $payment
     * @param Address|null $shippingAddressRegistered
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function processAssert(
        CheckoutOnepage $checkoutOnepage,
        TestStepFactory $testStepFactory,
        FixtureFactory $fixtureFactory,
        GiftCardAccount $giftCardAccount,
        Customer $customer,
        Address $shippingAddress,
        CatalogProductSimple $product,
        array $shipping,
        array $payment,
        Address $shippingAddressRegistered = null
    ) {
        $this->checkoutOnepage = $checkoutOnepage;
        $this->testStepFactory = $testStepFactory;
        $this->fixtureFactory = $fixtureFactory;

        if (!$customer->getId()) {
            $customer->persist();
        }
        
        if (!$product->getId()) {
            $product->persist();
        }

        if ($shippingAddressRegistered === null) {
            $shippingAddressRegistered = $this->fixtureFactory->create(
                Address::class,
                ['dataset' => 'US_address_1_without_email']
            );
        }

        // Create quote and apply gift card for registered customer.
        $this->prepareRegisteredCustomerQuote($customer, $giftCardAccount, $product);
        // Create new order with gift card for guest.
        $this->placeOrder($giftCardAccount, $shippingAddress, $product, $shipping, $payment);
        // Complete order creation for registered customer.
        $this->completeRegisteredCustomerOrder($customer, $shippingAddressRegistered, $shipping, $payment);
    }

    /**
     * Prepare quote for registered customer.
     *
     * @param Customer $customer
     * @param GiftCardAccount $giftCard
     * @param CatalogProductSimple $product
     * @return void
     */
    private function prepareRegisteredCustomerQuote(
        Customer $customer,
        GiftCardAccount $giftCard,
        CatalogProductSimple $product
    ) {
        $this->login($customer);

        // Add products to cart.
        $this->testStepFactory->create(AddProductsToTheCartStep::class, ['products' => [$product]])->run();
        // Proceed to checkout cart.
        $this->testStepFactory->create(ViewAndEditCartStep::class)->run();
        // Apply gift cart.
        $this->testStepFactory->create(ApplyGiftCardStep::class, ['giftCardAccount' => $giftCard])->run();

        $this->logout();
    }

    /**
     * Place order.
     *
     * @param GiftCardAccount $giftCard
     * @param Address $address
     * @param CatalogProductSimple $product
     * @param array $shipping
     * @param array $payment
     * @return void
     */
    private function placeOrder(
        GiftCardAccount $giftCard,
        Address $address,
        CatalogProductSimple $product,
        array $shipping,
        array $payment
    ) {
        // Add products to cart.
        $this->testStepFactory->create(AddProductsToTheCartStep::class, ['products' => [$product]])->run();
        // Proceed to checkout cart.
        $this->testStepFactory->create(ViewAndEditCartStep::class)->run();
        // Apply gift cart.
        $this->testStepFactory->create(ApplyGiftCardStep::class, ['giftCardAccount' => $giftCard])->run();
        // Proceed to checkout.
        $this->testStepFactory->create(ProceedToCheckoutStep::class)->run();
        // Fill shipping address.
        $this->testStepFactory->create(FillShippingAddressStep::class, ['shippingAddress' => $address])->run();
        // Select shipping method.
        $this->testStepFactory->create(FillShippingMethodStep::class, ['shipping' => $shipping])->run();
        // Select payment method.
        $this->testStepFactory->create(SelectPaymentMethodStep::class, ['payment' => $payment])->run();

        \PHPUnit_Framework_Assert::assertTrue(
            $this->checkoutOnepage->getReviewBlock()->isGiftCardApplied(),
            'Gift card is not applied.'
        );

        // Click "Place order" button.
        $result = $this->testStepFactory->create(PlaceOrderStep::class)->run();

        \PHPUnit_Framework_Assert::assertNotEmpty(
            $result['orderId'],
            'Order not placed.'
        );
    }

    /**
     * Process gift card account asserts.
     *
     * @param Customer $customer
     * @param Address $address
     * @param array $shipping
     * @param array $payment
     * @return void
     */
    private function completeRegisteredCustomerOrder(
        Customer $customer,
        Address $address,
        array $shipping,
        array $payment
    ) {
        $this->login($customer);

        // Proceed to checkout.
        $this->testStepFactory->create(ProceedToCheckoutStep::class)->run();
        // Fill shipping address.
        $this->testStepFactory->create(FillShippingAddressStep::class, ['shippingAddress' => $address])->run();
        // Select shipping method.
        $this->testStepFactory->create(FillShippingMethodStep::class, ['shipping' => $shipping])->run();
        // Select payment method.
        $this->testStepFactory->create(SelectPaymentMethodStep::class, ['payment' => $payment])->run();

        \PHPUnit_Framework_Assert::assertFalse(
            $this->checkoutOnepage->getReviewBlock()->isGiftCardApplied(),
            'Gift card is applied.'
        );

        // Click "Place order" button.
        $result = $this->testStepFactory->create(PlaceOrderStep::class)->run();

        \PHPUnit_Framework_Assert::assertNotEmpty(
            $result['orderId'],
            'Order not placed.'
        );

        $this->logout();
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Gift card can be used once exceeding balance amount.";
    }
}
