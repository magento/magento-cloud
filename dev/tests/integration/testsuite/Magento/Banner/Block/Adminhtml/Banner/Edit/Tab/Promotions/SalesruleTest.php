<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Block\Adminhtml\Banner\Edit\Tab\Promotions;

/**
 * @magentoDataFixture Magento/SalesRule/_files/cart_rule_40_percent_off.php
 * @magentoDataFixture Magento/SalesRule/_files/cart_rule_50_percent_off.php
 * @magentoAppArea adminhtml
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
        /** @var \Magento\SalesRule\Model\Rule $ruleOne */
        $ruleOne = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\SalesRule\Model\Rule');
        $ruleOne->load('40% Off on Large Orders', 'name');

        /** @var \Magento\SalesRule\Model\Rule $ruleTwo */
        $ruleTwo = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\SalesRule\Model\Rule');
        $ruleTwo->load('50% Off on Large Orders', 'name');

        $this->assertEquals([$ruleOne->getId(), $ruleTwo->getId()], $this->_block->getCollection()->getAllIds());
    }
}
