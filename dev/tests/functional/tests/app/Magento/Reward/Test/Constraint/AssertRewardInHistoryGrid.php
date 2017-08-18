<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\Reward\Test\Block\Adminhtml\Edit\Tab\Reward as RewardTab;
use Magento\Reward\Test\Fixture\Reward;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after updating reward balance - it reflects in history grid: check Points, Website, Comment.
 */
class AssertRewardInHistoryGrid extends AbstractConstraint
{
    /**
     * Assert that after updating reward balance - it reflects in history grid: check Points, Website, Comment.
     *
     * @param CustomerIndexEdit $customerIndexEdit
     * @param CustomerIndex $customerIndex
     * @param Customer $customer
     * @param Reward $reward
     * @return void
     */
    public function processAssert(
        CustomerIndexEdit $customerIndexEdit,
        CustomerIndex $customerIndex,
        Customer $customer,
        Reward $reward
    ) {
        $filter = ['email' => $customer->getEmail()];
        $customerIndex->open();
        $customerIndex->getCustomerGridBlock()->searchAndOpen($filter);
        $customerIndexEdit->getCustomerForm()->openTab('reward_points');

        /** @var RewardTab $rewardPointsTab */
        $rewardPointsTab = $customerIndexEdit->getCustomerForm()->getTab('reward_points');
        $rewardPointsTab->showRewardPointsHistoryGrid();
        $data = $reward->getData();
        if (isset($data['store_id'])) {
            $data['store_id'] = explode('/', $data['store_id'])[0];
        }

        \PHPUnit_Framework_Assert::assertTrue(
            $rewardPointsTab->getHistoryGrid()->isRowVisible($data, false),
            "Record in Reward Points History Grid was not found."
        );
    }

    /**
     * Returns string representation of assert.
     *
     * @return string
     */
    public function toString()
    {
        return 'Record in Reward Points History Grid was found.';
    }
}
