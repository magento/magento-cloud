<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Fixture;

use Magento\Mtf\Factory\Factory;

/**
 * Class GuestAuthorizenet
 * Credit Card (Authorize.net)
 * Guest checkout with Authorize.Net payment method and offline shipping method
 *
 */
class GuestAuthorizenet extends Checkout
{
    /**
     * Prepare Authorize.Net data
     */
    protected function _initData()
    {
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '156.81',
            ],
        ];
    }

    /**
     * Setup fixture
     */
    public function persist()
    {
        //Configuration
        $this->_persistConfiguration([
            'flat_rate',
            'authorizenet',
            'display_price',
            'display_shopping_cart',
            'default_tax_config',
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
            ['dataset' => 'US_address_1']
        );

        $this->shippingMethods = Factory::getFixtureFactory()->getMagentoShippingMethod();
        $this->shippingMethods->switchData('flat_rate');

        $this->paymentMethod = Factory::getFixtureFactory()->getMagentoPaymentMethod();
        $this->paymentMethod->switchData('authorizenet');

        $this->creditCard = $objectManager->create(
            'Magento\Payment\Test\Fixture\CreditCard',
            ['dataset' => 'visa_authorizenet']
        );
    }
}
