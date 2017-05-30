<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Fixture;

use Magento\Mtf\Factory\Factory;
use Magento\Mtf\ObjectManager;

/**
 * Class PaypalExpress
 * PayPal Express Method
 * Guest checkout using "Checkout with PayPal" button from product page and Free Shipping
 *
 */
class PaypalExpress extends Checkout
{
    /**
     * Paypal customer buyer
     *
     * @var \Magento\Paypal\Test\Fixture\Customer
     */
    private $paypalCustomer;

    /**
     * Customer telephone number
     *
     * @var string
     */
    private $telephoneNumber;

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
     * Get telephone number
     *
     * @return array
     */
    public function getTelephoneNumber()
    {
        return ['telephone' => $this->telephoneNumber];
    }

    /**
     * Prepare data for guest checkout using "Checkout with PayPal" button on product page
     */
    protected function _initData()
    {
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '10.83',
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
            'paypal_disabled_all_methods',
            'paypal_express',
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

        $this->products = [
            $simple,
        ];

        //Checkout data
        $this->billingAddress = $objectManager->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'address_US_pay_pal']
        );

        $this->shippingAddresses = $objectManager->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'address_US_pay_pal']
        );

        $this->shippingMethods = Factory::getFixtureFactory()->getMagentoShippingMethod();
        $this->shippingMethods->switchData('free_shipping');

        $this->paypalCustomer = Factory::getFixtureFactory()->getMagentoPaypalCustomer();
        $this->paypalCustomer->switchData('customer_US');

        $customerAddress = $objectManager->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'US_address_1']
        );
        $this->telephoneNumber = $customerAddress->getTelephone();
    }
}
