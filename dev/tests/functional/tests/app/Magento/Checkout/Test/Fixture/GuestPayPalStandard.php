<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Fixture;

use Magento\Mtf\Factory\Factory;

/**
 * Guest checkout with taxes, PayPal Standard payment method and offline shipping method
 *
 */
class GuestPayPalStandard extends Checkout
{
    /**
     * Paypal customer buyer
     *
     * @var \Magento\Paypal\Test\Fixture\Customer
     */
    private $paypalCustomer;

    /**
     * Prepare for PayPal Standard
     */
    protected function _initData()
    {
        $this->_data = [
            'totals' => [
                'grand_total' => '156.81',
                'comment_history' => 'Authorized amount of $156.81',
            ],
        ];
    }

    /**
     * Create required data
     */
    public function persist()
    {
        //Configuration
        $this->_persistConfiguration([
            'flat_rate',
            'paypal_disabled_all_methods',
            'paypal_standard',
            'default_tax_config',
            'display_price',
            'display_shopping_cart',
        ]);

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
            ['dataset' => 'customer_US']
        );

        $this->shippingMethods = Factory::getFixtureFactory()->getMagentoShippingMethod();
        $this->shippingMethods->switchData('flat_rate');

        $this->paymentMethod = Factory::getFixtureFactory()->getMagentoPaymentMethod();
        $this->paymentMethod->switchData('paypal_standard');

        $this->paypalCustomer = Factory::getFixtureFactory()->getMagentoPaypalCustomer();
        $this->paypalCustomer->switchData('customer_US');
    }

    /**
     * Get Paypal buyer account
     *
     * @return \Magento\Paypal\Test\Fixture\Customer
     */
    public function getPaypalCustomer()
    {
        return $this->paypalCustomer;
    }
}
