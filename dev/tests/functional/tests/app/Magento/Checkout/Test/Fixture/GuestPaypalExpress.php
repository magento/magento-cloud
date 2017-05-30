<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Fixture;

use Magento\Mtf\Factory\Factory;
use Magento\Mtf\ObjectManager;

/**
 * Class GuestPaypalExpress
 * PayPal Express Method
 * Guest checkout using PayPal Express Checkout method and offline shipping method
 *
 */
class GuestPaypalExpress extends Checkout
{
    /**
     * Paypal customer buyer
     *
     * @var \Magento\Paypal\Test\Fixture\Customer
     */
    private $paypalCustomer;

    /**
     * Get Paypal buyer account
     *
     * @return \Magento\Paypal\Test\Fixture\Customer
     */
    public function getPaypalCustomer()
    {
        return $this->paypalCustomer;
    }

    /**
     * Prepare data for guest checkout with PayPal Express
     */
    protected function _initData()
    {
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '156.81',
                'comment_history'   => 'Authorized amount of 156.81',
            ],
        ];
    }

    /**
     * Setup fixture
     */
    public function persist()
    {
        //Configuration
        $this->_persistConfiguration($this->_getConfigFixtures());

        //Tax
        Factory::getApp()->magentoTaxRemoveTaxRule();
        $objectManager = Factory::getObjectManager();
        $taxRule = $objectManager->create('Magento\Tax\Test\Fixture\TaxRule', ['dataset' => 'custom_rule']);
        $taxRule->persist();

        //Products
        $simple = $objectManager->create(
            'Magento\Catalog\Test\Fixture\CatalogProductSimple',
            ['dataset' => 'simple_10_dollar']
        );
        $simple->persist();
        $configurable = $objectManager->create(
            'Magento\ConfigurableProduct\Test\Fixture\ConfigurableProduct',
            ['dataset' => 'two_options_by_one_dollar']
        );
        $configurable->persist();
        $bundle = $objectManager->create(
            'Magento\Bundle\Test\Fixture\BundleProduct',
            [
                'dataset' => 'fixed_100_dollar_with_required_options'
            ]
        );
        $bundle->persist();

        $this->products = [
            $simple,
            $configurable,
            $bundle,
        ];

        //Checkout data
        $this->billingAddress = $objectManager->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'US_address_1']
        );

        $this->shippingMethods = Factory::getFixtureFactory()->getMagentoShippingMethod();
        $this->shippingMethods->switchData('flat_rate');

        $this->paymentMethod = Factory::getFixtureFactory()->getMagentoPaymentMethod();
        $this->paymentMethod->switchData('paypal_express');

        $this->creditCard = null;

        $this->paypalCustomer = Factory::getFixtureFactory()->getMagentoPaypalCustomer();
        $this->paypalCustomer->switchData('customer_US');
    }

    /**
     * Init billing address for checkout
     *
     * @return \Magento\Customer\Test\Fixture\Address
     */
    protected function _initBillingAddress()
    {
        return ObjectManager::getInstance()->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'US_address_1_without_email']
        );
    }

    /**
     * Get configuration fixtures
     *
     * @return array
     */
    protected function _getConfigFixtures()
    {
        return [
            'flat_rate',
            'paypal_disabled_all_methods',
            'paypal_express',
            'display_price',
            'display_shopping_cart',
            'default_tax_config'
        ];
    }
}
