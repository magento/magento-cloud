<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedCheckout\Block\Adminhtml\Manage;

/**
 * @magentoAppArea adminhtml
 */
class LoadTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Magento\Framework\View\LayoutInterface */
    protected $_layout = null;

    /** @var \Magento\AdvancedCheckout\Block\Adminhtml\Manage\Load */
    protected $_block = null;

    protected function setUp()
    {
        parent::setUp();
        $this->_layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        );
        $this->_block = $this->_layout->createBlock(\Magento\AdvancedCheckout\Block\Adminhtml\Manage\Load::class);
    }

    public function testToHtml()
    {
        $blockName = 'block1';
        $blockNameOne = 'block2';
        $containerName = 'container';
        $content = 'Content 1';
        $contentOne = 'Content 2';
        $containerContent = 'Content in container';

        $parent = $this->_block->getNameInLayout();
        $this->_layout->addBlock(\Magento\Framework\View\Element\Text::class, $blockName, $parent)->setText($content);
        $this->_layout->addContainer($containerName, 'Container', [], $parent);
        $this->_layout->addBlock(\Magento\Framework\View\Element\Text::class, '', $containerName)
            ->setText($containerContent);
        $this->_layout->addBlock(\Magento\Framework\View\Element\Text::class, $blockNameOne, $parent)
            ->setText($contentOne);

        $result = $this->_block->toHtml();
        $expectedDecoded = [
            $blockName => $content,
            $containerName => $containerContent,
            $blockNameOne => $contentOne,
        ];
        $this->assertEquals(
            $expectedDecoded,
            \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
                \Magento\Framework\Json\Helper\Data::class
            )->jsonDecode(
                $result
            )
        );
    }
}
