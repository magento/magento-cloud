<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Logging\Block\Adminhtml\Grid\Filter;

/**
 * @magentoAppArea adminhtml
 */
class IpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Logging\Block\Adminhtml\Grid\Filter\Ip
     */
    protected $_block;

    protected function setUp()
    {
        parent::setUp();
        $this->_block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        )->createBlock(
            'Magento\Logging\Block\Adminhtml\Grid\Filter\Ip'
        );
    }

    public function testGetCondition()
    {
        $condition = $this->_block->getCondition();
        $this->assertArrayHasKey('ntoa', $condition);
    }

    public function testGetConditionWithLike()
    {
        $this->_block->setValue('127');
        $condition = $this->_block->getCondition();
        $this->assertContains('127', (string)$condition['ntoa']);
        $this->assertNotEquals('127', (string)$condition['ntoa']); // DB-depended placeholder symbols were added
    }
}
