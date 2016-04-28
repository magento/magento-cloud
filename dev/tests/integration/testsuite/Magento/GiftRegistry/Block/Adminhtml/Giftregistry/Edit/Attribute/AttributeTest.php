<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftRegistry\Block\Adminhtml\Giftregistry\Edit\Attribute;

class AttributeTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Magento\Framework\View\LayoutInterface */
    protected $_layout = null;

    /** @var \Magento\GiftRegistry\Block\Adminhtml\Giftregistry\Edit\Attribute\Attribute */
    protected $_block = null;

    protected function setUp()
    {
        parent::setUp();
        $this->_layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        );
        $this->_block = $this->_layout->createBlock(
            'Magento\GiftRegistry\Block\Adminhtml\Giftregistry\Edit\Attribute\Attribute'
        );
    }

    public function testGetAddButtonId()
    {
        $block = $this->_block->getChildBlock('add_button');
        $expected = uniqid();
        $this->assertNotEquals($expected, $this->_block->getAddButtonId());
        $block->setId($expected);
        $this->assertEquals($expected, $this->_block->getAddButtonId());
    }
}
