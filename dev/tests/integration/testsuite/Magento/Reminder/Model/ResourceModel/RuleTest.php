<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\Reminder\Model\ResourceModel;

use Magento\Reminder\Model\Rule;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * RuleTest class.
 */
class RuleTest extends \Magento\TestFramework\Indexer\TestCase
{
    /**
     * @magentoDataFixture Magento/Reminder/_files/customersForNotification.php
     * @magentoConfigFixture current_store catalog/frontend/flat_catalog_product 1
     */
    public function testGetCustomersForNotification()
    {
        $beforeYesterday = date('Y-m-d 03:00:00', strtotime('-2 day', time()));
        $customersForNotification = [['customer_id' => '1', 'coupon_id' => null, 'rule_id' => null, 'schedule' => '2',
                'log_sent_at_max' => $beforeYesterday, 'log_sent_at_min' => $beforeYesterday, ]];
        /** @var \Magento\Framework\App\ResourceConnection $resource */
        $resource = Bootstrap::getObjectManager()->get(\Magento\Framework\App\ResourceConnection::class);
        $connection = $resource->getConnection();
        $connection->query("UPDATE {$resource->getTableName('quote')} SET updated_at = '{$beforeYesterday}'");

        $collection = Bootstrap::getObjectManager()->create(
            \Magento\Reminder\Model\ResourceModel\Rule\Collection::class
        );
        $rules = $collection->addIsActiveFilter(1);
        $ruleResource = Bootstrap::getObjectManager()->create(\Magento\Reminder\Model\ResourceModel\Rule::class);
        foreach ($rules as $rule) {
            $customersForNotification[0]['rule_id'] = $rule->getId();
            $connection->query("INSERT INTO {$resource->getTableName('magento_reminder_rule_log')} " .
                "(`rule_id`, `customer_id`, `sent_at`) VALUES ({$rule->getId()}, 1, '{$beforeYesterday}');");
            $websiteIds = $rule->getWebsiteIds();
            foreach ($websiteIds as $websiteId) {
                $ruleResource->saveMatchedCustomers($rule, null, $websiteId, null);
            }
        }
        $this->assertEquals($customersForNotification, $ruleResource->getCustomersForNotification());
    }
}
