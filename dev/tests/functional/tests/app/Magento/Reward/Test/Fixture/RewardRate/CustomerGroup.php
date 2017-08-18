<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Fixture\RewardRate;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Customer\Test\Fixture\CustomerGroup as CustomerGroupFixture;

/**
 * Prepare data for customer_group_id field in reward fixture.
 *
 * Data keys:
 *  - dataset
 */
class CustomerGroup extends DataSource
{
    /**
     * Customer Group fixture.
     *
     * @var CustomerGroupFixture
     */
    protected $customerGroup;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data = [])
    {
        $this->params = $params;
        if (isset($data['dataset'])) {
            /** @var CustomerGroupFixture $customerGroup */
            $customerGroup = $fixtureFactory->createByCode('customerGroup', ['dataset' => $data['dataset']]);
            if (!$customerGroup->hasData('customer_group_id')) {
                $customerGroup->persist();
            }
            $this->customerGroup = $customerGroup;
            $this->data = $customerGroup->getCustomerGroupCode();
        }
    }

    /**
     * Return customer group fixture.
     *
     * @return CustomerGroup
     */
    public function getCustomerGroup()
    {
        return $this->customerGroup;
    }
}
