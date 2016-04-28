<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\Fixture;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Fixture\DataFixture;
use Magento\Mtf\ObjectManager;

/**
 * Fixture with all necessary data for order creation on backend
 */
class Order extends DataFixture
{
    /**
     * Customer
     *
     * @var \Magento\Customer\Test\Fixture\Customer
     */
    protected $customer;

    /**
     * Products for order
     *
     * @var CatalogProductSimple[]
     */
    protected $products = [];

    /**
     * Customer billing address
     *
     * @var \Magento\Customer\Test\Fixture\Address
     */
    protected $billingAddress;

    /**
     * Customer shipping addresses
     *
     * @var \Magento\Customer\Test\Fixture\Address
     */
    protected $shippingAddress;

    /**
     * New shipping address that should be added during order creation
     *
     * @var \Magento\Customer\Test\Fixture\Address
     */
    protected $newShippingAddress;

    /**
     * Shipping method
     *
     * @var \Magento\Shipping\Test\Fixture\Method
     */
    protected $shippingMethod;

    /**
     * Payment method
     *
     * @var \Magento\Payment\Test\Fixture\Method
     */
    protected $paymentMethod;

    /**
     * {inheritdoc}
     */
    protected function _initData()
    {
        $this->_data = [
            'totals' => [
                'grand_total' => '32.73',
            ],
            'store_view' => 'Default Store View',
            'website_id' => '0',
        ];
    }

    public function persist()
    {
        //Configuration
        $this->_persistConfiguration(
            [
                'flat_rate',
                'default_tax_config',
            ]
        );
        //Tax
        Factory::getApp()->magentoTaxRemoveTaxRule();
        $objectManager = Factory::getObjectManager();
        $taxRule = $objectManager->create('Magento\Tax\Test\Fixture\TaxRule', ['dataset' => 'custom_rule']);
        $taxRule->persist();
        //Products
        $simple = Factory::getObjectManager()->create(
            'Magento\Catalog\Test\Fixture\CatalogProductSimple',
            ['dataset' => 'simple_10_dollar']
        );
        $simple->persist();

        $configurable = Factory::getObjectManager()->create(
            'Magento\ConfigurableProduct\Test\Fixture\ConfigurableProduct',
            ['dataset' => 'with_one_option']
        );
        $configurable->persist();

        $this->products = [
            $simple,
            $configurable,
        ];
        //Checkout data
        $this->billingAddress = ObjectManager::getInstance()->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'US_address_1_without_email']
        );

        $this->shippingMethod = Factory::getFixtureFactory()->getMagentoShippingMethod();
        $this->shippingMethod->switchData('flat_rate');

        $this->paymentMethod = Factory::getFixtureFactory()->getMagentoPaymentMethod();
        $this->paymentMethod->switchData('check_money_order');

        return $this;
    }

    /**
     * Get product which should be added to order
     *
     * @return CatalogProductSimple[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Get customer data for order creation
     *
     * @return \Magento\Customer\Test\Fixture\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Get customer billing address
     *
     * @return \Magento\Customer\Test\Fixture\Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Get customer shipping address
     *
     * @return \Magento\Customer\Test\Fixture\Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Get new shipping address
     *
     * @return \Magento\Customer\Test\Fixture\Address
     */
    public function getNewShippingAddress()
    {
        return $this->newShippingAddress;
    }

    /**
     * Get shipping method data
     *
     * @return \Magento\Shipping\Test\Fixture\Method
     */
    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }

    /**
     * Get payment method data
     *
     * @return \Magento\Payment\Test\Fixture\Method
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Get name of store view where the order should be created
     *
     * @return string
     */
    public function getStoreViewName()
    {
        return $this->getData('store_view');
    }

    /**
     * Get order grand total amount
     *
     * @return string
     */
    public function getGrandTotal()
    {
        return $this->getData('totals/grand_total');
    }

    /**
     * Setup a set of configurations
     *
     * @param array $datasets
     */
    protected function _persistConfiguration(array $datasets)
    {
        $configFixture = Factory::getFixtureFactory()->getMagentoConfigConfig();
        foreach ($datasets as $dataset) {
            $configFixture->switchData($dataset);
            $configFixture->persist();
        }
    }
}
