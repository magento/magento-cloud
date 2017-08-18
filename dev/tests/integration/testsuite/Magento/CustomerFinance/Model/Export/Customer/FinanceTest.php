<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerFinance\Model\Export\Customer;

use Magento\CustomerFinance\Model\ResourceModel\Customer\Attribute\Finance\Collection as FinanceAttributeCollection;

/**
 * @magentoConfigFixture current_store magento_reward/general/is_enabled            1
 * @magentoConfigFixture current_store customer/magento_customerbalance/is_enabled  1
 */
class FinanceTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Store\Model\StoreManagerInterface::class
        )->reinitStores();
    }

    /**
     * Test export data
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customer_finance.php
     */
    public function testExport()
    {
        $customerFinance = $this->_createCustomerFinanceExport();
        $customerFinance->setParameters([]);
        $csvExportString = $customerFinance->export();

        // get data from CSV file
        list($csvHeader, $csvData) = $this->_getCsvData($csvExportString);
        $this->assertCount(
            count(
                \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
                    \Magento\Store\Model\StoreManagerInterface::class
                )->getWebsites()
            ),
            $csvData
        );

        // prepare correct header
        $correctHeader = $customerFinance->getPermanentAttributes();
        $attributeCollection = $customerFinance->getAttributeCollection();
        foreach ($customerFinance->filterAttributeCollection($attributeCollection) as $attribute) {
            /** @var $attribute \Magento\Eav\Model\Entity\Attribute */
            $correctHeader[] = $attribute->getAttributeCode();
        }

        sort($csvHeader);
        sort($correctHeader);
        $this->assertEquals($correctHeader, $csvHeader);

        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $websites = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getWebsites();
        /** @var $website \Magento\Store\Model\Website */
        foreach ($websites as $website) {
            $websiteCode = $website->getCode();
            // CSV data
            $csvCustomerData = $this->_getRecordByFinanceWebsite($csvData, $websiteCode);
            $this->assertNotNull($csvCustomerData, 'Customer data for website "' . $websiteCode . '" must exist.');

            // prepare correct data
            $correctCustomerData = [
                Finance::COLUMN_EMAIL => $objectManager->get(
                    \Magento\Framework\Registry::class
                )->registry(
                    'customer_finance_email'
                ),
                Finance::COLUMN_WEBSITE => \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
                    \Magento\Store\Model\StoreManagerInterface::class
                )->getStore()->getWebsite()->getCode(),
                Finance::COLUMN_FINANCE_WEBSITE => $websiteCode,
                FinanceAttributeCollection::COLUMN_CUSTOMER_BALANCE => $objectManager->get(
                    \Magento\Framework\Registry::class
                )->registry(
                    'customer_balance_' . $websiteCode
                ),
                FinanceAttributeCollection::COLUMN_REWARD_POINTS => $objectManager->get(
                    \Magento\Framework\Registry::class
                )->registry(
                    'reward_point_balance_' . $websiteCode
                ),
            ];

            asort($csvCustomerData);
            asort($correctCustomerData);
            $this->assertEquals($correctCustomerData, $csvCustomerData);
        }
    }

    /**
     * Test export data with store_credit and reward_points filters
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customer_finance.php
     */
    public function testExportWithFilters()
    {
        $customerFinance = $this->_createCustomerFinanceExport();
        $customerFinance->setParameters(
            [
                'export_filter' => [
                    'store_credit' => [55, 65],
                    'reward_points' => [0, 110]
                ]
            ]
        );
        $csvExportString = $customerFinance->export();

        // get data from CSV file
        list(, $csvData) = $this->_getCsvData($csvExportString);
        $this->assertCount(0, $csvData);
    }

    /**
     * Test export data with reward_point column excluded
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customer_finance.php
     */
    public function testExportWithExcludes()
    {
        $customerFinance = $this->_createCustomerFinanceExport();
        $customerFinance->setParameters(
            [
                'skip_attr' => ['2']
            ]
        );
        $csvExportString = $customerFinance->export();

        list($csvHeader, $csvData) = $this->_getCsvData($csvExportString);
        $this->assertContains('store_credit', $csvHeader, implode(',', $csvHeader));
        $this->assertNotContains('reward_points', $csvHeader, implode(',', $csvHeader));
        $this->assertCount(count($csvHeader), $csvData[0]);
    }

    /**
     * Test method testGetAttributeCollection
     */
    public function testGetAttributeCollection()
    {
        $customerFinance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerFinance\Model\Export\Customer\Finance::class
        );
        $attributeCollection = $customerFinance->getAttributeCollection();

        $this->assertInstanceOf(
            \Magento\CustomerFinance\Model\ResourceModel\Customer\Attribute\Finance\Collection::class,
            $attributeCollection
        );
    }

    /**
     * Get CSV header and data from string as array (header, data)
     *
     * @param string $csvString
     * @return array
     */
    protected function _getCsvData($csvString)
    {
        list($csvHeaderString, $csvDataString) = explode("\n", $csvString, 2);
        $csvHeader = str_getcsv($csvHeaderString);

        $csvData = explode("\n", $csvDataString);
        foreach ($csvData as $key => $csvRecordString) {
            $csvRecordString = trim($csvRecordString);
            if ($csvRecordString) {
                $csvRecord = str_getcsv($csvRecordString);
                $csvRecord = array_combine($csvHeader, $csvRecord);
                $csvData[$key] = $csvRecord;
            } else {
                unset($csvData[$key]);
            }
        }

        return [$csvHeader, $csvData];
    }

    /**
     * Get record by finance data
     *
     * @param array $records
     * @param string $website
     * @return array|null
     */
    protected function _getRecordByFinanceWebsite(array $records, $website)
    {
        $financeWebsiteKey = Finance::COLUMN_FINANCE_WEBSITE;
        foreach ($records as $record) {
            if ($record[$financeWebsiteKey] == $website) {
                return $record;
            }
        }
        return null;
    }

    /**
     * Returns object with writer Magento\ImportExport\Model\Export\Adapter\Csv
     *
     * @return \Magento\CustomerFinance\Model\Export\Customer\Finance
     */
    protected function _createCustomerFinanceExport()
    {
        $customerFinance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerFinance\Model\Export\Customer\Finance::class
        );
        $customerFinance->setWriter(
            \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
                \Magento\ImportExport\Model\Export\Adapter\Csv::class
            )
        );
        return $customerFinance;
    }
}
