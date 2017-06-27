<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Promotions;

/**
 * @magentoDataFixture Magento/SalesRule/_files/cart_rule_40_percent_off.php
 * @magentoDataFixture Magento/SalesRule/_files/cart_rule_50_percent_off.php
 */
class SalesruleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Promotions\Salesrule
     */
    private $_block;

    protected function setUp()
    {
        $this->_block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        )->createBlock(
            'Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Promotions\Salesrule'
        );
    }

    protected function tearDown()
    {
        $this->_block = null;
    }

    public function testGetCollection()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $registry = $objectManager->get('Magento\Framework\Registry');
        /** @var \Magento\SalesRule\Model\Rule $ruleOne */
        $ruleOne = $objectManager->create('Magento\SalesRule\Model\Rule');
        $ruleOneId = $registry->registry('Magento/SalesRule/_files/cart_rule_40_percent_off');
        $ruleOne->load($ruleOneId);

        /** @var \Magento\SalesRule\Model\Rule $ruleTwo */
        $ruleTwo = $objectManager->create('Magento\SalesRule\Model\Rule');
        $ruleTwoId = $registry->registry('Magento/SalesRule/_files/cart_rule_50_percent_off');
        $ruleTwo->load($ruleTwoId);

        $items = $this->_block->getCollection()
            ->addFieldToFilter('main_table.rule_id', ['in' => [$ruleOne->getId(), $ruleTwo->getId()]])
            ->getItems();

        $actualArray = [];
        foreach ($items as $item) {
            $actualArray[] = $item->getData('rule_id');
        }
        sort($actualArray);
        $expected = [$ruleOne->getId(), $ruleTwo->getId()];
        sort($expected);
        $this->assertEquals($expected, $actualArray);
    }
}
