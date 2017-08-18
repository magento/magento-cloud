<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Reward\Block\Adminhtml\Reward\Rate\Edit;

/**
 * @magentoAppArea adminhtml
 */
class FormTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Magento\Reward\Block\Adminhtml\Reward\Rate\Edit\Form */
    protected $_block;

    protected function setUp()
    {
        parent::setUp();
        $layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Framework\View\Layout::class
        );
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        if (!$objectManager->get(\Magento\Framework\Registry::class)->registry('current_reward_rate')) {
            $rate = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
                \Magento\Reward\Model\Reward\Rate::class
            );
            $objectManager->get(\Magento\Framework\Registry::class)->register('current_reward_rate', $rate);
        }

        $this->_block = $layout->createBlock(\Magento\Reward\Block\Adminhtml\Reward\Rate\Edit\Form::class);
    }

    /**
     * Test Prepare Form in Single Store mode
     *
     * @magentoConfigFixture current_store general/single_store_mode/enabled 1
     */
    public function testPrepareFormSingleStore()
    {
        $this->_block->toHtml();
        $form = $this->_block->getForm();
        $this->assertInstanceOf(\Magento\Framework\Data\Form::class, $form);
        $this->assertNull($form->getElement('website_id'));
    }

    /**
     * Test Prepare Form in Multiple Store mode
     *
     * @magentoConfigFixture current_store general/single_store_mode/enabled 0
     */
    public function testPrepareFormMultipleStore()
    {
        $this->_block->toHtml();
        $form = $this->_block->getForm();
        $this->assertInstanceOf(\Magento\Framework\Data\Form::class, $form);
        $element = $form->getElement('website_id');
        $this->assertNotNull($element);
        $this->assertInstanceOf(\Magento\Framework\Data\Form\Element\Select::class, $element);
        $this->assertEquals('website_id', $element->getId());
    }
}
