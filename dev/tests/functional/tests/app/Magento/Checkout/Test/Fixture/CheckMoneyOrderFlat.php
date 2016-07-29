<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Fixture;

use Magento\Catalog\Test\Fixture;
use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Config\DataInterface;

/**
 * Guest checkout with Check/Money order payment method, flat shipping method, no tax.
 *
 */
class CheckMoneyOrderFlat extends Checkout
{
    /**
     * Custom constructor
     *
     * @param DataInterface $configuration
     * @param array $placeholders
     */
    public function __construct(DataInterface $configuration, $placeholders = [])
    {
        parent::__construct($configuration, $placeholders);

        $this->products = $placeholders['products'];
    }

    /**
     * Prepare data
     */
    protected function _initData()
    {
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '$21.00',
                'sub_total' => '$11.00',
            ],
        ];
    }

    /**
     * Persist prepared data into application
     */
    public function persist()
    {
        //Configuration
        $this->_persistConfiguration(['flat_rate', 'check_money_order']);

        //Tax
        Factory::getApp()->magentoTaxRemoveTaxRule();

        //Checkout data
        $objectManager = Factory::getObjectManager();
        $this->billingAddress = $objectManager->create(
            'Magento\Customer\Test\Fixture\Address',
            ['dataset' => 'US_address_1']
        );

        $this->shippingMethods = Factory::getFixtureFactory()->getMagentoShippingMethod();
        $this->shippingMethods->switchData('flat_rate');

        $this->paymentMethod = Factory::getFixtureFactory()->getMagentoPaymentMethod();
        $this->paymentMethod->switchData('check_money_order');
    }
}
