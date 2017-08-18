<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerFinance\Test\Constraint;

use Magento\CustomerBalance\Test\Fixture\CustomerBalance;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Util\Command\File\ExportInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Util\Command\File\Export\Data;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Reward\Test\Fixture\Reward;

/**
 * Assert that exported file contains customer finances data.
 */
class AssertExportCustomerFinances extends AbstractConstraint
{
    /**
     * Assert that exported file contains customer finances data.
     *
     * @param ExportInterface $export
     * @param array $customersData
     * @return void
     */
    public function processAssert(
        ExportInterface $export,
        array $customersData
    ) {
        $exportData = $export->getLatest();

        foreach ($customersData['customers'] as $key => $customer) {
            \PHPUnit_Framework_Assert::assertTrue(
                $this->isFinanceDataInFile(
                    $customer,
                    $customersData['customersRewardPoints'][$key],
                    $customersData['customersBalanceData'][$key],
                    $exportData
                ),
                'Customer finance data was not found in exported file.'
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer finances data exists in exported file.';
    }

    /**
     * Get customer address data from exported file.
     *
     * @param Customer $customer
     * @param Reward $customerRewardPoints
     * @param CustomerBalance $customerBalance
     * @param Data $exportData
     * @return bool
     */
    private function isFinanceDataInFile(
        Customer $customer,
        Reward $customerRewardPoints,
        CustomerBalance $customerBalance,
        Data $exportData
    ) {
        $regexp = '/';
        $regexp .= '.*(' . $customer->getData('email') . ')';
        $regexp .= $this->prepareWebsiteFieldRegexp($customer);
        $regexp .= $this->prepareWebsiteFieldRegexp($customerBalance);
        $regexp .= '.*(' . $customerBalance->getData('balance_delta') . ')';
        $regexp .= '.*(' . $customerRewardPoints->getData('points_delta') . ')';
        $regexp .= '/U';
        return (bool) preg_match($regexp, $exportData->getContent());
    }

    /**
     * Prepare regexp for fixture website field.
     *
     * @param FixtureInterface $fixture
     * @return string
     */
    private function prepareWebsiteFieldRegexp(FixtureInterface $fixture)
    {
        return (preg_match('/[0-9]/', $fixture->getData('website_id'))) ?
            '.*(' . preg_replace('/\D/', '', $fixture->getData('website_id')) . ')' : '.*(base)';
    }
}
