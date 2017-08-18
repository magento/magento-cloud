<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Model\ResourceModel\Salesrule;

/**
 * @magentoDataFixture Magento/Banner/_files/banner_disabled_40_percent_off.php
 * @magentoDataFixture Magento/Banner/_files/banner_enabled_40_to_50_percent_off.php
 */
class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Banner\Model\ResourceModel\Salesrule\Collection
     */
    private $_collection;

    protected function setUp()
    {
        $this->_collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Banner\Model\ResourceModel\Salesrule\Collection::class
        );
    }

    protected function tearDown()
    {
        $this->_collection = null;
    }

    public function testGetItems()
    {
        /** @var \Magento\Banner\Model\Banner $banner */
        $banner = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(\Magento\Banner\Model\Banner::class);
        $banner->load('Get from 40% to 50% Off on Large Orders', 'name');

        $this->assertCount(1, $this->_collection->getItems());
        $this->assertEquals($banner->getId(), $this->_collection->getFirstItem()->getData('banner_id'));
    }

    public function testAddRuleIdsFilter()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $registry = $objectManager->get(\Magento\Framework\Registry::class);

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule = $objectManager->create(\Magento\SalesRule\Model\Rule::class);
        $ruleId = $registry->registry('Magento/SalesRule/_files/cart_rule_40_percent_off');
        $rule->load($ruleId);

        $this->_collection->addRuleIdsFilter([$rule->getId()]);

        $this->testGetItems();
    }

    public function testAddRuleIdsFilterNoRules()
    {
        $this->_collection->addRuleIdsFilter([]);

        $this->assertEmpty($this->_collection->getItems());
    }
}
