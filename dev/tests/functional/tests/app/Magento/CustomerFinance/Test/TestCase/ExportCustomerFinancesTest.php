<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerFinance\Test\TestCase;

use Magento\CustomerBalance\Test\Fixture\CustomerBalance;
use Magento\ImportExport\Test\Page\Adminhtml\AdminExportIndex;
use Magento\ImportExport\Test\Fixture\ExportData;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;
use Magento\Reward\Test\Fixture\Reward;

/**
 * Preconditions:
 * 1. Create customer.
 *
 * Steps:
 * 1. Login to admin.
 * 2. Navigate to System > Export.
 * 3. Select Entity Type = Customer Finances.
 * 4. Fill Entity Attributes data.
 * 5. Click "Continue".
 * 6. Perform all assertions.
 *
 * @group ImportExport
 * @ZephyrId MAGETWO-46178
 */
class ExportCustomerFinancesTest extends Injectable
{
    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Admin export index page.
     *
     * @var AdminExportIndex
     */
    private $adminExportIndex;

    /**
     * Inject data.
     *
     * @param FixtureFactory $fixtureFactory
     * @param AdminExportIndex $adminExportIndex
     * @return void
     */
    public function __inject(
        FixtureFactory $fixtureFactory,
        AdminExportIndex $adminExportIndex
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $this->adminExportIndex = $adminExportIndex;
    }

    /**
     * Runs Export Customer Addresses test.
     *
     * @param ExportData $exportData
     * @param array $customers
     * @return array
     */
    public function test(
        ExportData $exportData,
        array $customers
    ) {
        $customersData = $this->prepareCustomers($customers);
        $exportData->persist();

        $this->adminExportIndex->open();
        $this->adminExportIndex->getExportForm()->fill($exportData);
        $this->adminExportIndex->getFilterExport()->clickContinue();

        return [
            'customersData' => $customersData
        ];
    }

    /**
     * Prepare data for all customers.
     *
     * @param array $customers
     * @return array
     */
    private function prepareCustomers(array $customers)
    {
        $createdCustomers = [];
        $rewardPointsData = [];
        $customerBalanceData = [];
        foreach ($customers as $customer) {
            $customerData = $customer;
            $data = [];
            if (isset($customerData['store'])) {
                $data = [
                    'website_id' => [
                        'dataset' => $customerData['store']
                    ]
                ];
            }
            /** @var Customer $customer */
            $customer = $this->fixtureFactory->createByCode(
                $customerData['fixture'],
                [
                    'dataset' => $customerData['dataset'],
                    'data' => $data
                ]
            );
            $customer->persist();
            $createdCustomers[] = $customer;

            $rewardPointsData[] = $this->prepareCustomerReward($customer, $customerData['rewardPoints']);
            $customerBalanceData[] = $this->prepareCustomerBalance($customer, $customerData['customerBalance']);
        }

        return [
            'customers' => $createdCustomers,
            'customersRewardPoints' => $rewardPointsData,
            'customersBalanceData' => $customerBalanceData
        ];
    }

    /**
     * Prepare customer reward points data.
     *
     * @param Customer $customer
     * @param string $rewardPoints
     * @return Reward
     */
    private function prepareCustomerReward(Customer $customer, $rewardPoints)
    {
        /** @var Reward $reward */
        $reward = $this->fixtureFactory->createByCode(
            'reward',
            [
                'dataset' => $rewardPoints,
                'data' => [
                    'customer_id' => ['customer' => $customer],
                    'store_id' => $customer->getDataFieldConfig('website_id')['source']->getStore()->getStoreId()
                ]
            ]
        );
        $reward->persist();

        return $reward;
    }

    /**
     * Prepare customer balance data.
     *
     * @param Customer $customer
     * @param string $customerBalance
     * @return CustomerBalance
     */
    private function prepareCustomerBalance(Customer $customer, $customerBalance)
    {
        /** @var CustomerBalance $customerBalance */
        $customerBalance = $this->fixtureFactory->createByCode(
            'customerBalance',
            [
                'dataset' => $customerBalance,
                'data' => [
                    'customer_id' => [
                        'customer' => $customer
                    ],
                    'website_id' => [
                        'fixture' => $customer->getDataFieldConfig('website_id')['source']->getWebsite()
                    ]
                ]
            ]
        );
        $customerBalance->persist();

        return $customerBalance;
    }
}
