<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Fixture;

use Magento\Mtf\Factory\Factory;
use Magento\Mtf\ObjectManager;

/**
 * Class SpecialPriceCheckMoneyOrder
 * Registered shoppers checkout using check or money order
 *
 */
class SpecialPriceCheckMoneyOrder extends Checkout
{
    /**
     * Configurable product
     *
     * @var \Magento\ConfigurableProduct\Test\Fixture\ConfigurableProduct
     */
    protected $configurableProduct;

    /**
     * Simple product
     *
     * @var \Magento\Catalog\Test\Fixture\CatalogProductSimple
     */
    protected $simpleProduct;

    /**
     * Return the configurable product
     *
     * @return \Magento\ConfigurableProduct\Test\Fixture\ConfigurableProduct
     */
    public function getConfigurableProduct()
    {
        return $this->configurableProduct;
    }

    /**
     * Return the simple product
     *
     * @return \Magento\Catalog\Test\Fixture\CatalogProductSimple
     */
    public function getSimpleProduct()
    {
        return $this->simpleProduct;
    }

    /**
     * Prepare data for registered customer checkout with check or money order
     */
    protected function _initData()
    {
        // Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '$30.57',
            ],
        ];
    }

    /**
     * Setup fixture
     */
    public function persist()
    {
        // Configuration
        $this->_persistConfiguration([
            'flat_rate',
            'enable_mysql_search',
         ]);

        // Tax
        Factory::getApp()->magentoTaxRemoveTaxRule();
        $objectManager = Factory::getObjectManager();
        $taxRule = $objectManager->create('Magento\Tax\Test\Fixture\TaxRule', ['dataset' => 'custom_rule']);
        $taxRule->persist();

        // Products with advanced pricing
        $this->simpleProduct = ObjectManager::getInstance()->create(
            'Magento\Catalog\Test\Fixture\CatalogProductSimple',
            [
                'dataset' => 'simple_10_dollar',
                'data' => [
                    'special_price' => 9
                ]
            ]
        );
        $this->simpleProduct->persist();

        $this->configurableProduct = ObjectManager::getInstance()->create(
            'Magento\ConfigurableProduct\Test\Fixture\ConfigurableProduct',
            [
                'dataset' => 'two_options_by_one_dollar',
                'data' => [
                    'special_price' => 9
                ]
            ]
        );
        $this->configurableProduct->persist();

        $this->products = [
            $this->simpleProduct,
            $this->configurableProduct,
        ];

        //Checkout data
        $this->customer = ObjectManager::getInstance()->create(
            'Magento\Customer\Test\Fixture\Customer',
            ['dataset' => 'customer_US']
        );
        $this->customer->persist();

        $this->billingAddress = $objectManager->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'US_address_1_without_email']
        );

        $this->shippingMethods = Factory::getFixtureFactory()->getMagentoShippingMethod();
        $this->shippingMethods->switchData('flat_rate');

        $this->paymentMethod = Factory::getFixtureFactory()->getMagentoPaymentMethod();
        $this->paymentMethod->switchData('check_money_order');

        return $this;
    }
}
