<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Reminder\Model\ResourceModel\Rule;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoDataFixture Magento/Reminder/_files/rules.php
     */
    public function testAddDateFilter()
    {
        $dateModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Framework\Stdlib\DateTime\DateTime'
        );
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Reminder\Model\ResourceModel\Rule\Collection'
        );
        $collection->addDateFilter($dateModel->date());
        $this->assertEquals(1, $collection->count());
        foreach ($collection as $rule) {
            $this->assertInstanceOf('Magento\Reminder\Model\Rule', $rule);
            $this->assertEquals('Rule 2', $rule->getName());
            return;
        }
    }
}
