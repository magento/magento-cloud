<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftRegistry\Block\Customer;

class EditTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\GiftRegistry\Block\Customer\Edit
     */
    protected $_block;

    protected function setUp()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(\Magento\Framework\App\State::class)
            ->setAreaCode('frontend');
        $this->_block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        )->createBlock(
            \Magento\GiftRegistry\Block\Customer\Edit::class
        );
    }

    public function testAddInputTypeTemplate()
    {
        $this->assertEmpty($this->_block->getInputTypeTemplate('test'));
        $this->_block->addInputTypeTemplate('test', 'Magento_GiftRegistry::attributes/text.phtml');
        $template = $this->_block->getInputTypeTemplate('test');
        $this->assertFileExists($template);
        $this->assertStringEndsWith('attributes/text.phtml', $template);
    }
}
