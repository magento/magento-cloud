<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Fixture;

use Magento\Mtf\Factory\Factory;

/**
 * AutomaticTaxApplying checkout fixture
 *
 */
class AutomaticTaxApplying extends Checkout
{
    /**
     * Simple product
     *
     * @var \Magento\Catalog\Test\Fixture\CatalogProductSimple
     */
    protected $simpleProduct;

    /**
     * Customer vat group fixture
     *
     * @var \Magento\Customer\Test\Fixture\VatGroup
     */
    protected $customerVatGroup;

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
     * Prepare data
     */
    protected function _initData()
    {
        // Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '17.00',
            ],
            'cart_totals' => [
                'grand_total' => '12.00',
                'subtotal' => '10.00',
                'tax' => '2.00',
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
            'check_money_order',
            'display_price',
            'display_shopping_cart',
        ]);

        // Customer VAT groups
        $this->customerVatGroup = Factory::getFixtureFactory()->getMagentoCustomerVatGroup();
        $this->customerVatGroup->switchData('customer_UK_with_VAT');
        $this->customerVatGroup->persist();
        $this->customer = $this->customerVatGroup->getCustomer();

        // Tax
        Factory::getApp()->magentoTaxRemoveTaxRule();
        $objectManager = Factory::getObjectManager();
        $taxRule = $objectManager->create('Magento\Tax\Test\Fixture\TaxRule', ['dataset' => 'uk_full_tax_rule']);
        $taxRule->persist();

        // Simple Products
        $this->simpleProduct = $objectManager->create(
            'Magento\Catalog\Test\Fixture\CatalogProductSimple',
            ['dataset' => 'simple_10_dollar']
        );
        $this->simpleProduct->persist();
        $this->products = [
            $this->simpleProduct,
        ];

        // Checkout data
        $this->shippingMethods = Factory::getFixtureFactory()->getMagentoShippingMethod();
        $this->shippingMethods->switchData('flat_rate');

        $this->paymentMethod = Factory::getFixtureFactory()->getMagentoPaymentMethod();
        $this->paymentMethod->switchData('check_money_order');
        return $this;
    }

    /**
     * Get group name for valid VAT intra-union
     *
     * @return string
     */
    public function getValidVatIntraUnionGroup()
    {
        return $this->customerVatGroup->getValidVatIntraUnionGroup();
    }

    /**
     * Get created customer groups ids
     *
     * @return array
     */
    public function getGroupsIds()
    {
        return $this->customerVatGroup->getGroupsIds();
    }

    /**
     * Get cart grand total
     *
     * @return string
     */
    public function getCartGrandTotal()
    {
        return $this->getData('cart_totals/grand_total');
    }

    /**
     * Get cart subtotal
     *
     * @return string
     */
    public function getCartSubtotal()
    {
        return $this->getData('cart_totals/subtotal');
    }

    /**
     * Get cart tax
     *
     * @return string
     */
    public function getCartTax()
    {
        return $this->getData('cart_totals/tax');
    }
}
