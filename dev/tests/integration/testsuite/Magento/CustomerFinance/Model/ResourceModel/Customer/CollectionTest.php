<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\CustomerFinance\Model\ResourceModel\Customer;

use Magento\CustomerFinance\Model\ResourceModel\Customer\Attribute\Finance\Collection as FinanceAttributeCollection;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Test collection \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection
 *
 * @magentoConfigFixture current_store magento_reward/general/is_enabled            1
 * @magentoConfigFixture current_store customer/magento_customerbalance/is_enabled  1
 */
class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Remove not used websites
     */
    protected function tearDown()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Store\Model\StoreManagerInterface::class
        )->reinitStores();
    }

    /**
     * Test join with reward points
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customer_finance.php
     */
    public function testJoinWithRewardPoints()
    {
        /** @var $collection \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection */
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection::class
        );
        $collection->joinWithRewardPoints(
            $this->getCustomerEntityAttributeCollection(),
            $this->getFinanceEntityAttributeCollection()
        );
        $items = $collection->getItems();
        $this->assertCount(1, $items);

        /** @var $customer \Magento\Customer\Model\Customer */
        $customer = reset($items);
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var $website \Magento\Store\Model\Website */
        $websites = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getWebsites();
        foreach ($websites as $website) {
            $key = $website->getCode() . '_' . FinanceAttributeCollection::COLUMN_REWARD_POINTS;
            $rewardPoints = $customer->getData($key);
            $this->assertNotEmpty($rewardPoints);
            $this->assertEquals(
                $objectManager->get(\Magento\Framework\Registry::class)
                    ->registry('reward_point_balance_' . $website->getCode()),
                $rewardPoints
            );
        }
    }

    /**
     * Test join with customer balance
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customer_finance.php
     */
    public function testJoinWithCustomerBalance()
    {
        /** @var $collection \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection */
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection::class
        );
        $collection->joinWithCustomerBalance(
            $this->getCustomerEntityAttributeCollection(),
            $this->getFinanceEntityAttributeCollection()
        );
        $items = $collection->getItems();
        $this->assertCount(1, $items);

        /** @var $customer \Magento\Customer\Model\Customer */
        $customer = reset($items);
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $websites = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getWebsites();
        /** @var $website \Magento\Store\Model\Website */
        foreach ($websites as $website) {
            $key = $website->getCode() . '_' . FinanceAttributeCollection::COLUMN_CUSTOMER_BALANCE;
            $customerBalance = $customer->getData($key);
            $this->assertNotEmpty($customerBalance);
            $this->assertEquals(
                $objectManager->get(
                    \Magento\Framework\Registry::class)->registry('customer_balance_' . $website->getCode()),
                $customerBalance
            );
        }
    }

    /**
     * Test filter with reward points and customer balance
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customer_finance_all_cases.php
     */
    public function testFilterWithRewardPointsAndCustomerBalance()
    {
        /** @var $collection \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection */
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection::class
        );
        $collection
            ->joinWithCustomerBalance(
                $this->getCustomerEntityAttributeCollection(),
                $this->getFinanceEntityAttributeCollection()
            )
            ->joinWithRewardPoints(
                $this->getCustomerEntityAttributeCollection(),
                $this->getFinanceEntityAttributeCollection()
            );
        $items = $collection->getItems();
        $this->assertCount(3, $items);

        $emails = [];
        foreach ($items as $item) {
            $emails[] = $item->getEmail();
        }
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_rp_cb'),
            $emails
        );
        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_rp'),
            $emails
        );
        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_cb'),
            $emails
        );
        $this->assertNotContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email'),
            $emails
        );
    }

    /**
     * Test filter only with reward points
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customer_finance_all_cases.php
     */
    public function testFilterWithRewardPoints()
    {
        /** @var $collection \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection */
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection::class
        );
        $collection->joinWithRewardPoints(
            $this->getCustomerEntityAttributeCollection(),
            $this->getFinanceEntityAttributeCollection()
        );
        $items = $collection->getItems();
        $this->assertCount(2, $items);

        $emails = [];
        foreach ($items as $item) {
            $emails[] = $item->getEmail();
        }
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_rp_cb'),
            $emails
        );
        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_rp'),
            $emails
        );
        $this->assertNotContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_cb'),
            $emails
        );
        $this->assertNotContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email'),
            $emails
        );
    }

    /**
     * Test filter only with customer balance
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customer_finance_all_cases.php
     */
    public function testFilterWithCustomerBalance()
    {
        /** @var $collection \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection */
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection::class
        );
        $collection->joinWithCustomerBalance(
            $this->getCustomerEntityAttributeCollection(),
            $this->getFinanceEntityAttributeCollection()
        );
        $items = $collection->getItems();
        $this->assertCount(2, $items);

        $emails = [];
        foreach ($items as $item) {
            $emails[] = $item->getEmail();
        }
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_rp_cb'),
            $emails
        );
        $this->assertNotContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_rp'),
            $emails
        );
        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_cb'),
            $emails
        );
        $this->assertNotContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email'),
            $emails
        );
    }

    /**
     * Test filter only without customer balance and reward points
     *
     * @magentoDataFixture Magento/ScheduledImportExport/_files/customer_finance_all_cases.php
     */
    public function testFilterWithoutRewardPointsAndCustomerBalance()
    {
        /** @var $collection \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection */
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CustomerFinance\Model\ResourceModel\Customer\Collection::class
        );
        $items = $collection->getItems();
        $this->assertCount(4, $items);

        $emails = [];
        foreach ($items as $item) {
            $emails[] = $item->getEmail();
        }

        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_rp_cb'),
            $emails
        );
        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_rp'),
            $emails
        );
        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email_cb'),
            $emails
        );
        $this->assertContains(
            $objectManager->get(\Magento\Framework\Registry::class)->registry('customer_finance_email'),
            $emails
        );
    }

    /**
     * @return \Magento\Customer\Model\ResourceModel\Attribute\Collection
     */
    protected function getCustomerEntityAttributeCollection()
    {
        /** @var \Magento\CustomerImportExport\Model\Export\Customer $entity */
        $entity = Bootstrap::getObjectManager()->get(\Magento\CustomerImportExport\Model\Export\Customer::class);
        return $entity->getAttributeCollection();
    }

    /**
     * @return \Magento\Framework\Data\Collection
     */
    protected function getFinanceEntityAttributeCollection()
    {
        /** @var \Magento\CustomerFinance\Model\Export\Customer\Finance $entity */
        $entity = Bootstrap::getObjectManager()->get(\Magento\CustomerFinance\Model\Export\Customer\Finance::class);
        return $entity->getAttributeCollection();
    }
}
