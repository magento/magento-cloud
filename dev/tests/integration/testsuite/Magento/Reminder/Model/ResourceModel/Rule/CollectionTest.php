<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Reminder\Model\ResourceModel\Rule;

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoDataFixture Magento/Reminder/_files/rules.php
     */
    public function testAddDateFilter()
    {
        $expectedConditionsSerialized = json_encode(
            [
                "type" => \Magento\Reminder\Model\Rule\Condition\Combine\Root::class,
                "attribute" => null,
                "operator" => null,
                "value" => true,
                "is_value_processed" => null,
                "aggregator" => "all"
            ]
        );
        $dateModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Framework\Stdlib\DateTime\DateTime::class
        );
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Reminder\Model\ResourceModel\Rule\Collection::class
        );
        $collection->addDateFilter($dateModel->date());
        $this->assertEquals(1, $collection->count());
        foreach ($collection as $rule) {
            $this->assertInstanceOf(\Magento\Reminder\Model\Rule::class, $rule);
            $this->assertEquals('Rule 2', $rule->getName());
            $this->assertEquals(
                $expectedConditionsSerialized,
                $rule->getConditionsSerialized()
            );
            return;
        }
    }
}
