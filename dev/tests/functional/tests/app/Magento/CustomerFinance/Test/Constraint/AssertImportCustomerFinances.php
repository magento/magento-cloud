<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerFinance\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\ImportExport\Test\Fixture\ImportData;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;

/**
 * Assert products data from csv import file and page are match.
 */
class AssertImportCustomerFinances extends AbstractConstraint
{
    /**
     * No records message.
     *
     * @var string
     */
    private $noticeMessage = "We couldn't find any records.";

    /**
     * Currency.
     *
     * @var string
     */
    private $currency = '$';

    /**
     * Import fixture.
     *
     * @var ImportData
     */
    private $import;

    /**
     * CustomerIndexEdit page.
     *
     * @var CustomerIndexEdit
     */
    private $customerIndexEdit;

    /**
     * Assert imported customers finances are correct.
     *
     * @param CustomerIndexEdit $customerIndexEdit
     * @param ImportData $import
     * @return void
     */
    public function processAssert(
        CustomerIndexEdit $customerIndexEdit,
        ImportData $import
    ) {
        $this->import = $import;
        $this->customerIndexEdit = $customerIndexEdit;

        \PHPUnit_Framework_Assert::assertEquals(
            $this->getResultCustomerFinance(),
            $this->getResultCsv(),
            'Customers finances from page and csv are not match.'
        );
    }

    /**
     * Prepare customers finances data.
     *
     * @return array
     */
    protected function getResultCustomerFinance()
    {
        $resultCustomersData = [];
        $customers = $this->import->getDataFieldConfig('import_file')['source']->getEntities();
        foreach ($customers as $customer) {
            // Get reward points and store credits.
            $this->customerIndexEdit->open(['id' => $customer->getId()]);
            $grid = $this->customerIndexEdit->getCustomerForm()->getTab('reward_points');
            $rewardPoints = '0';
            if ($grid->getRewardPointsGridRow(1) !== $this->noticeMessage) {
                $rewardPoints = $grid->getRewardPointsGridRow(2);
            }
            $grid = $this->customerIndexEdit->getCustomerBalanceForm()->getStoreCreditTab();
            $storeCredit = $grid->getStoreGridRow(1);
            if ($storeCredit === $this->noticeMessage) {
                $storeCredit = $this->currency . '0.00';
            }
            $resultCustomersData[] = [
                'email' => $customer->getEmail(),
                'reward_points' => $rewardPoints,
                'store_credit' => $storeCredit
            ];
        }
        return $resultCustomersData;
    }

    /**
     * Prepare array from csv file.
     *
     * @return array
     */
    private function getResultCsv()
    {
        $resultCsvData = [];
        $csvData = $this->import->getDataFieldConfig('import_file')['source']->getCsv();
        $csvKeys = array_shift($csvData);
        foreach ($csvData as $data) {
            $data = array_combine($csvKeys, $data);
            if ('Delete Entities' === $this->import->getData()['behavior']) {
                $resultCsvData[] = [
                    'email' => $data['_email'],
                    'reward_points' => '0',
                    'store_credit' => $this->currency . '0.00'
                ];
                continue;
            }
            $resultCsvData[] = [
                'email' => $data['_email'],
                'reward_points' => $data['reward_points'],
                'store_credit' => $this->currency . number_format($data['store_credit'], 2)
            ];
        }
        return $resultCsvData;
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Imported customers finances are correct.';
    }
}
