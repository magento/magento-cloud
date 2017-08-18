<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Test class for \Magento\CustomerFinance\Model\Import\Eav\Customer\Finance
 */
namespace Magento\CustomerFinance\Model\Import\Eav\Customer;

use Magento\CustomerFinance\Model\ResourceModel\Customer\Attribute\Finance\Collection;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FinanceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Remove test website
     */
    protected function tearDown()
    {
        /** @var $testWebsite \Magento\Store\Model\Website */
        $testWebsite = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Store\Model\Website::class
        )->load(
            'test'
        );
        if ($testWebsite->getId()) {
            // Clear test website info from application cache.
            \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
                \Magento\Store\Model\StoreManagerInterface::class
            )->reinitStores();
        }
    }

    /**
     * Test import data method
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customer_finance_all_cases.php
     * @magentoDataFixture Magento/Store/_files/website.php
     * @magentoAppArea adminhtml
     *
     * @codingStandardsIgnoreStart
     * @covers \Magento\CustomerFinance\Model\Import\Eav\Customer\Finance::_importData
     * @covers \Magento\CustomerFinance\Model\Import\Eav\Customer\Finance::_updateRewardPointsForCustomer
     * @covers \Magento\CustomerFinance\Model\Import\Eav\Customer\Finance::_updateCustomerBalanceForCustomer
     * @codingStandardsIgnoreEnd
     * @covers \Magento\CustomerFinance\Model\Import\Eav\Customer\Finance::_getComment
     */
    public function testImportData()
    {
        /**
         * Try to get test website instance,
         * in this case test website will be added into protected property of Application instance class.
         */
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var $testWebsite \Magento\Store\Model\Website */
        $testWebsite = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Store\Model\Website::class
        )->load(
            'test'
        );
        $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getWebsite($testWebsite->getId());

        // load websites to have ability get website code by id.
        $websiteCodes = [];
        $websites = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getWebsites();
        /** @var $website \Magento\Store\Model\Website */
        foreach ($websites as $website) {
            $websiteCodes[$website->getId()] = $website->getCode();
        }

        $userName = 'TestAdmin';
        $user = new \Magento\Framework\DataObject(['username' => $userName]);
        /** @var $session \Magento\Backend\Model\Auth\Session */
        $session = $objectManager->get(\Magento\Backend\Model\Auth\Session::class);
        $session->setUser($user);

        $directory = $objectManager->create(
            \Magento\Framework\Filesystem::class
        )->getDirectoryWrite(
            DirectoryList::ROOT
        );

        $pathToCsvFile = __DIR__ . '/../_files/customer_finance.csv';
        $expectedFinanceData = $this->_csvToArray(file_get_contents($pathToCsvFile));

        $source = new \Magento\ImportExport\Model\Import\Source\Csv($pathToCsvFile, $directory);
        /** @var \Magento\CustomerFinance\Model\Import\Eav\Customer\Finance $model */
        $model = $objectManager->create(\Magento\CustomerFinance\Model\Import\Eav\Customer\Finance::class);
        $model->setParameters(['behavior' => \Magento\ImportExport\Model\Import::BEHAVIOR_ADD_UPDATE]);
        $model->setSource($source);
        $model->validateData();
        $model->importData();

        $rewardPointsKey = Collection::COLUMN_REWARD_POINTS;
        $customerBalanceKey = Collection::COLUMN_CUSTOMER_BALANCE;

        $customerCollection = $objectManager->create(\Magento\Customer\Model\ResourceModel\Customer\Collection::class);
        /** @var $customer \Magento\Customer\Model\Customer */
        foreach ($customerCollection as $customer) {
            $rewardCollection = $objectManager->create(\Magento\Reward\Model\ResourceModel\Reward\Collection::class);
            $rewardCollection->addFieldToFilter('customer_id', $customer->getId());
            /** @var $rewardPoints \Magento\Reward\Model\Reward */
            foreach ($rewardCollection as $rewardPoints) {
                $websiteCode = $websiteCodes[$rewardPoints->getWebsiteId()];
                $expected = $expectedFinanceData[$customer->getEmail()][$websiteCode][$rewardPointsKey];
                if ($expected < 0) {
                    $expected = 0;
                }
                $this->assertEquals(
                    $expected,
                    $rewardPoints->getPointsBalance(),
                    'Reward points value was not updated'
                );
            }

            $customerBalance = $objectManager->create(
                \Magento\CustomerBalance\Model\ResourceModel\Balance\Collection::class
            );
            $customerBalance->addFieldToFilter('customer_id', $customer->getId());
            /** @var $balance \Magento\CustomerBalance\Model\Balance */
            foreach ($customerBalance as $balance) {
                $websiteCode = $websiteCodes[$balance->getWebsiteId()];
                $expected = $expectedFinanceData[$customer->getEmail()][$websiteCode][$customerBalanceKey];
                if ($expected < 0) {
                    $expected = 0;
                }
                $this->assertEquals($expected, $balance->getAmount(), 'Customer balance value was not updated');
            }
        }
    }

    /**
     * Test import data method
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customers_for_finance_import_delete.php
     * @magentoAppArea adminhtml
     *
     * @covers \Magento\CustomerFinance\Model\Import\Eav\Customer\Finance::_importData
     * @covers \Magento\CustomerFinance\Model\Import\Eav\Customer\Finance::_deleteRewardPoints
     * @covers \Magento\CustomerFinance\Model\Import\Eav\Customer\Finance::_deleteCustomerBalance
     */
    public function testImportDataDelete()
    {
        /* clean up the database from prior tests before importing */
        $rewards = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Reward\Model\ResourceModel\Reward\Collection::class
        );
        foreach ($rewards as $reward) {
            $reward->delete();
        }

        $directory = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Framework\Filesystem::class
        )->getDirectoryWrite(
            DirectoryList::ROOT
        );
        $source = new \Magento\ImportExport\Model\Import\Source\Csv(
            __DIR__ . '/../_files/customer_finance_delete.csv',
            $directory
        );
        $model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerFinance\Model\Import\Eav\Customer\Finance::class
        );
        $model->setParameters(['behavior' => \Magento\ImportExport\Model\Import::BEHAVIOR_DELETE]);
        $model->setSource($source);
        $model->validateData();
        $model->importData();

        $rewards = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Reward\Model\ResourceModel\Reward\Collection::class
        );
        $balances = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerBalance\Model\ResourceModel\Balance\Collection::class
        );
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $expectedRewards = $objectManager->get(
            \Magento\Framework\Registry::class
        )->registry(
            '_fixture/Magento_ScheduledImportExport_Customers_ExpectedRewards'
        );
        /** @var $reward \Magento\Reward\Model\Reward */
        foreach ($rewards as $reward) {
            $this->assertEquals(
                $reward->getPointsBalance(),
                $expectedRewards[$reward->getCustomerId()][$reward->getWebsiteId()]
            );
        }

        $expectedBalances = $objectManager->get(
            \Magento\Framework\Registry::class
        )->registry(
            '_fixture/Magento_ScheduledImportExport_Customers_ExpectedBalances'
        );
        /** @var $balance \Magento\CustomerBalance\Model\Balance */
        foreach ($balances as $balance) {
            $this->assertEquals(
                $balance->getAmount(),
                $expectedBalances[$balance->getCustomerId()][$balance->getWebsiteId()]
            );
        }
    }

    /**
     * Export CSV finance data to array
     *
     * @param string $content
     * @return array
     */
    protected function _csvToArray($content)
    {
        $emailKey = Finance::COLUMN_EMAIL;
        $websiteKey = Finance::COLUMN_FINANCE_WEBSITE;

        $header = [];
        $data = [];
        $lines = str_getcsv($content, "\n");
        foreach ($lines as $index => $line) {
            if ($index == 0) {
                $header = str_getcsv($line);
            } else {
                $row = array_combine($header, str_getcsv($line));
                if (!isset($data[$row[$emailKey]])) {
                    $data[$row[$emailKey]] = [];
                }
                $data[$row[$emailKey]][$row[$websiteKey]] = $row;
            }
        }
        return $data;
    }
}
