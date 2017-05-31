<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\Catalog\Test\TestStep\CreateProductsStep;
use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Checkout\Test\TestStep\AddNewShippingAddressStep;
use Magento\Checkout\Test\TestStep\AddProductsToTheCartStep;
use Magento\Checkout\Test\TestStep\ProceedToCheckoutStep;
use Magento\Customer\Test\Fixture\Address;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\TestStep\EditCustomerDefaultAddressOnFrontendStep;
use Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerAddressAttribute;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Assert that created Customer Address Attribute is present in customer address on checkout page.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AssertCustomerAddressAttributeVisibleOnCheckout extends AbstractConstraint
{
    const ERROR_MESSAGE = "Customer Address Attribute with value '%s' is absent in selected address.";

    /**
     * Checkout page.
     *
     * @var CheckoutOnepage
     */
    private $checkoutOnepage;

    /**
     * Customer address attribute.
     *
     * @var CustomerAddressAttribute
     */
    private $customerAddressAttribute;

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
     * Processed steps.
     *
     * @var TestStepInterface[]
     */
    private $tearDownSteps = [];

    /**
     * Assert that created customer address attribute is present in customer address on checkout page.
     *
     * @param CheckoutOnepage $checkoutOnepage
     * @param CustomerAddressAttribute $customerAddressAttribute
     * @param Customer $customer
     * @param Address $address
     * @param array $products
     * @param TestStepFactory $testStepFactory
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function processAssert(
        CheckoutOnepage $checkoutOnepage,
        CustomerAddressAttribute $customerAddressAttribute,
        Customer $customer,
        Address $address,
        array $products,
        TestStepFactory $testStepFactory,
        FixtureFactory $fixtureFactory
    ) {
        $this->checkoutOnepage = $checkoutOnepage;
        $this->customerAddressAttribute = $customerAddressAttribute;
        $this->testStepFactory = $testStepFactory;
        $this->fixtureFactory = $fixtureFactory;

        try {
            $this->runSteps($customer, $address, $products);
        } finally {
            $this->clearSteps();
        }
    }

    /**
     * Run required steps before making assertions.
     *
     * @param Customer $customer
     * @param Address $address
     * @param array $products
     * @return void
     */
    private function runSteps(
        Customer $customer,
        Address $address,
        array $products
    ) {
        $customer->persist();

        $attributeValue = 'default_address_attribute_' . time();
        /** @var Address $defaultAddress */
        $defaultAddress = $this->getAddress($attributeValue);

        // Login customer on frontend.
        $customerLoginStep = $this->testStepFactory->create(
            LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        );
        $customerLoginStep->run();

        $this->tearDownSteps[] = $customerLoginStep;

        // Change default shipping address (add custom attribute value).
        $this->testStepFactory->create(
            EditCustomerDefaultAddressOnFrontendStep::class,
            [
                'address' => $defaultAddress,
                'addressType' => 'shipping'
            ]
        )->run();

        // Create simple products.
        $result = $this->testStepFactory->create(
            CreateProductsStep::class,
            [
                'fixtureFactory' => $this->fixtureFactory,
                'products' => $products
            ]
        )->run();
        $products = $result['products'];

        // Add product to shopping cart.
        $this->testStepFactory->create(
            AddProductsToTheCartStep::class,
            ['products' => $products]
        )->run();

        // Proceed to checkout.
        $this->testStepFactory->create(ProceedToCheckoutStep::class)->run();

        \PHPUnit_Framework_Assert::assertContains(
            $attributeValue,
            $this->checkoutOnepage->getShippingBlock()->getSelectedAddress(),
            sprintf(self::ERROR_MESSAGE, $attributeValue)
        );

        $attributeValue = 'new_address_attribute_' . time();
        $address = $this->getAddress($attributeValue, $address->getData());

        // Add new shipping address.
        $this->testStepFactory->create(
            AddNewShippingAddressStep::class,
            [
                'checkoutOnepage' => $this->checkoutOnepage,
                'address' => $address
            ]
        )->run();

        $this->checkoutOnepage->getShippingBlock()->selectAddress($address->getStreet());

        \PHPUnit_Framework_Assert::assertContains(
            $attributeValue,
            $this->checkoutOnepage->getShippingBlock()->getSelectedAddress(),
            sprintf(self::ERROR_MESSAGE, $attributeValue)
        );
    }

    /**
     * Return address.
     *
     * @param string $customAttributeValue
     * @param array $initialData
     * @return Address
     */
    private function getAddress($customAttributeValue, $initialData = [])
    {
        $attributeData = [
            'custom_attribute' => [
                'attribute' => $this->customerAddressAttribute,
                'value' => $customAttributeValue,
            ]
        ];
        $data = array_merge($initialData, $attributeData);

        return $this->fixtureFactory->createByCode('address', ['data' => $data]);
    }

    /**
     * Perform steps cleanup.
     *
     * @return void
     */
    private function clearSteps()
    {
        foreach ($this->tearDownSteps as $step) {
            if (method_exists($step, 'cleanup')) {
                $step->cleanup();
            }
        }
        $this->tearDownSteps = [];
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Address Attribute is present in customer address on checkout page.';
    }
}
