<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Fixture\CustomerBalance;

use Magento\Mtf\Fixture\Datasource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Customer\Test\Fixture\Customer;

/**
 * Prepare data for customer_id field in customer balance fixture.
 *
 * Data keys:
 *  - dataset
 *  - customer
 */
class CustomerId extends Datasource
{
    /**
     * Customer fixture
     *
     * @var Customer
     */
    protected $customer;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data = [])
    {
        $this->params = $params;
        if (isset($data['customer']) && $data['customer'] instanceof Customer) {
            $this->customer = $data['customer'];
            $this->data = $this->customer->getEmail();
        }
        if (isset($data['dataset'])) {
            /** @var Customer $customer */
            $customer = $fixtureFactory->createByCode('customer', ['dataset' => $data['dataset']]);
            if (!$customer->hasData('id')) {
                $customer->persist();
            }
            $this->customer = $customer;
            $this->data = $customer->getEmail();
        }
    }

    /**
     * Return customer fixture
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
