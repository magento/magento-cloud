<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Multishipping\Test\Fixture;

use Magento\Checkout\Test\Fixture\Checkout;
use Magento\Customer\Test\Fixture\Address;
use Magento\Mtf\Factory\Factory;

/**
 * PayPal Payments Pro Method.
 * Register on checkout to checkout with multi shipping using PayPal Payments Pro payment method.
 *
 */
class GuestPaypalDirect extends Checkout
{
    /**
     * Mapping between products and shipping addresses for multishipping.
     *
     * @var array
     */
    protected $bindings = [];

    /**
     * Data for guest multishipping checkout with Payments Pro Method.
     */
    protected function _initData()
    {
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => [
                    '15.83', //simple
                    '16.92', //configurable
                ],
            ],
        ];
    }

    /**
     * Get bindings for multishipping.
     *
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * Setup fixture.
     */
    public function persist()
    {
        //Configuration
        $this->_persistConfiguration([
            'flat_rate',
            'paypal_disabled_all_methods',
            'paypal_direct',
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

        $this->products = [
            $simple,
            $configurable,
        ];
        //Checkout data
        $this->customer = $objectManager->create(
            'Magento\Customer\Test\Fixture\Customer',
            ['dataset' => 'customer_US']
        );
        /** @var Address $address1 */
        $address1 = $objectManager->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'US_address_1']
        );
        /** @var Address $address2 */
        $address2 = $objectManager->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'US_address_2']
        );
        $this->shippingAddresses = [
            $address1,
            $address2,
        ];

        $newShippingAddress = $objectManager->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'US_address_2']
        );
        $this->newShippingAddresses = [$newShippingAddress];

        $shippingMethod1 = Factory::getFixtureFactory()->getMagentoShippingMethod();
        $shippingMethod1->switchData('flat_rate');
        $shippingMethod2 = Factory::getFixtureFactory()->getMagentoShippingMethod();
        $shippingMethod2->switchData('flat_rate');
        $this->shippingMethods = [
            $shippingMethod1,
            $shippingMethod2,
        ];
        $this->paymentMethod = Factory::getFixtureFactory()->getMagentoPaymentMethod();
        $this->paymentMethod->switchData('paypal_direct');
        $this->creditCard = $objectManager->create(
            'Magento\Payment\Test\Fixture\CreditCard',
            ['dataset' => 'visa_direct']
        );
        $this->bindings = [
            $simple->getName() => $this->getOneLineAddress($address1),
            $configurable->getName() => $this->getOneLineAddress($address2)
        ];
    }

    /**
     * Return one line address.
     *
     * @param Address $address
     * @return string
     */
    protected function getOneLineAddress(Address $address)
    {
        $data = $address->getData();
        $oneLineAddress = isset($data['prefix']) ? $data['prefix'] . ' ' : ''
            . $data['firstname'] . ' '
            . (isset($data['middlename']) ? $data['middlename'] . ' ' : '')
            . $data['lastname'] . ', '
            . (isset($data['suffix']) ? $data['suffix'] . ' ' : '')
            . $data['street'] . ', '
            . $data['city'] . ', '
            . $data['region_id'] . ' '
            . $data['postcode'] . ', '
            . $data['country_id'];

        return $oneLineAddress;
    }
}
