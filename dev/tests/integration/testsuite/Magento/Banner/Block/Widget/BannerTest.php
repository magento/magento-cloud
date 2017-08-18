<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Block\Widget;

/**
 * @magentoAppArea frontend
 */
class BannerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoAppIsolation enabled
     */
    public function testAddWidgetBanner()
    {
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        );
        /** @var \Magento\Banner\Block\Widget\Banner $block */
        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Banner\Block\Widget\Banner::class
        );
        $block->setTemplate('widget/block.phtml');
        $layout->addBlock($block, 'block1');
        $this->assertArrayHasKey('block1', $layout->getAllBlocks());
    }
}
