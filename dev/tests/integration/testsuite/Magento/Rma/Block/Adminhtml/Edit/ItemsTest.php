<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Block\Adminhtml\Edit;

/**
 * @magentoAppArea adminhtml
 */
class ItemsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoDataFixture Magento/Rma/_files/rma.php
     */
    public function testToHtml()
    {
        $rma = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Rma\Model\Rma::class);
        $rma->load(1, 'increment_id');
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $objectManager->get(\Magento\Framework\Registry::class)->register('current_rma', $rma);
        $utility = new \Magento\Framework\View\Utility\Layout($this);
        $layoutArguments = array_merge($utility->getLayoutDependencies(), ['area' => 'adminhtml']);
        $layout = $utility->getLayoutFromFixture(
            __DIR__ . '/../../../_files/adminhtml_rma_edit.xml',
            $layoutArguments
        );
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->addSharedInstance($layout, \Magento\TestFramework\View\Layout::class);
        $layout->getUpdate()->addHandle('adminhtml_rma_edit')->load();
        $layout->generateXml()->generateElements();
        $layout->addOutputElement('magento_rma_edit_tab_items');
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\DesignInterface::class
        )->setArea(
            'adminhtml'
        );
        $this->assertContains('<div id="magento_rma_item_edit_grid"', $layout->getOutput());
    }
}
