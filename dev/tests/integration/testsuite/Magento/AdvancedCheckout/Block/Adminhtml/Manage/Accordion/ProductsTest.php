<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedCheckout\Block\Adminhtml\Manage\Accordion;

/**
 * @magentoAppArea adminhtml
 */
class ProductsTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Magento\Framework\View\Element\AbstractBlock */
    protected $_block;

    protected function setUp()
    {
        parent::setUp();
        $layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        );
        $this->_block = $layout->createBlock(
            \Magento\AdvancedCheckout\Block\Adminhtml\Manage\Accordion\Products::class
        );
    }

    public function testPrepareLayout()
    {
        $searchBlock = $this->_block->getChildBlock('search_button');
        $this->assertInstanceOf(\Magento\Backend\Block\Widget\Button::class, $searchBlock);
        $this->assertEquals('checkoutObj.searchProducts()', $searchBlock->getOnclick());
    }
}
