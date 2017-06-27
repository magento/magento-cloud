<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Fixture;

use Magento\Bundle\Test\Fixture\BundleProduct;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\ConfigurableProduct\Test\Fixture\ConfigurableProduct;
use Magento\Mtf\Factory\Factory;

/**
 * Guest checkout with taxes, Check/Money order payment method and offline shipping method
 */
class CheckMoneyOrder extends Checkout
{
    /**
     * Prepare Check/Money order data
     */
    protected function _initData()
    {
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '141.81',
                'sub_total' => '131.00',
                'tax' => '10.81',
            ],
            'product_price_with_tax' => [
                'CatalogProductSimple' => [
                    'value' => '10.00',
                ],
                'ConfigurableProduct' => [
                    'value' => '11.00',
                ],
                'BundleProduct' => [
                    'value' => '110.00',
                ],
            ],
        ];
    }

    /**
     * Persists prepared data into application
     */
    public function persist()
    {
        //Configuration
        $this->_persistConfiguration([
            'free_shipping',
            'check_money_order',
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
        $this->shippingMethods->switchData('free_shipping');

        $this->paymentMethod = Factory::getFixtureFactory()->getMagentoPaymentMethod();
        $this->paymentMethod->switchData('check_money_order');
    }

    /**
     * Get Product Price with tax for product of particular class
     *
     * @param CatalogProductSimple $product
     * @return string
     */
    public function getProductPriceWithTax($product)
    {
        $className = explode('\\', get_class($product));
        return $this->getData('product_price_with_tax/' . $className[count($className) - 1] . '/value');
    }

    /**
     * Get order subtotal
     *
     * @return string
     */
    public function getSubtotal()
    {
        return $this->getData('totals/sub_total');
    }

    /**
     * Get order tax
     *
     * @return string
     */
    public function getTax()
    {
        return $this->getData('totals/tax');
    }
}
