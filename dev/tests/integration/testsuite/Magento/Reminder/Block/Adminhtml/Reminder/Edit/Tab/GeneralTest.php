<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Reminder\Block\Adminhtml\Reminder\Edit\Tab;

/**
 * Test class for \Magento\Reminder\Block\Adminhtml\Reminder\Edit\Tab\General
 * @magentoAppArea adminhtml
 */
class GeneralTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoAppIsolation enabled
     */
    public function testPrepareForm()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\DesignInterface::class
        )->setArea(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE
        )->setDefaultDesignTheme();
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $objectManager->get(
            \Magento\Framework\Registry::class
        )->register(
            'current_reminder_rule',
            \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Reminder\Model\Rule::class)
        );

        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        )->createBlock(
            \Magento\Reminder\Block\Adminhtml\Reminder\Edit\Tab\General::class
        );
        $prepareFormMethod = new \ReflectionMethod(
            \Magento\Reminder\Block\Adminhtml\Reminder\Edit\Tab\General::class,
            '_prepareForm'
        );
        $prepareFormMethod->setAccessible(true);
        $prepareFormMethod->invoke($block);

        $form = $block->getForm();
        foreach (['from_date', 'to_date'] as $id) {
            $element = $form->getElement($id);
            $this->assertNotNull($element);
            $this->assertNotEmpty($element->getDateFormat());
        }
        $element = $form->getElement('salesrule_id');
        $this->assertNotNull($element);
        $this->assertRegExp('#http://[a-z\./sales_rule/promo_quote/chooser/.*]#', $element->getAfterElementHtml());
    }
}
