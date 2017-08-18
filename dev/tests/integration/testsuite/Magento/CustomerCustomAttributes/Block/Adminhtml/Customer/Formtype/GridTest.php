<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerCustomAttributes\Block\Adminhtml\Customer\Formtype;

/**
 * @magentoAppArea adminhtml
 */
class GridTest extends \PHPUnit\Framework\TestCase
{
    public function testPrepareColumns()
    {
        /** @var \Magento\CustomerCustomAttributes\Block\Adminhtml\Customer\Formtype\Grid $block */
        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        )->createBlock(
            \Magento\CustomerCustomAttributes\Block\Adminhtml\Customer\Formtype\Grid::class
        );
        $block->toHtml();
        foreach (['code', 'label', 'store_id', 'theme', 'is_system'] as $key) {
            $this->assertInstanceOf(\Magento\Backend\Block\Widget\Grid\Column::class, $block->getColumn($key));
        }
        $this->assertNotEmpty($block->getColumn('theme')->getOptions());
    }
}
