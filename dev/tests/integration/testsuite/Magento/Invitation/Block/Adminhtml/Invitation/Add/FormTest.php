<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Invitation\Block\Adminhtml\Invitation\Add;

/**
 * Test class for \Magento\Invitation\Block\Adminhtml\Invitation\Add\Form
 *
 * @magentoAppArea adminhtml
 */
class FormTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoAppIsolation enabled
     */
    public function testPrepareFormForCustomerGroup()
    {
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $objectManager->get(
            \Magento\Framework\View\DesignInterface::class
        )->setArea(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE
        )->setDefaultDesignTheme();

        $block = $objectManager->create(\Magento\Invitation\Block\Adminhtml\Invitation\Add\Form::class);
        $block->setLayout($objectManager->create(\Magento\Framework\View\Layout::class));
        $prepareFormMethod = new \ReflectionMethod(
            \Magento\Invitation\Block\Adminhtml\Invitation\Add\Form::class,
            '_prepareForm'
        );
        $prepareFormMethod->setAccessible(true);
        $prepareFormMethod->invoke($block);

        $form = $block->getForm();

        $element = $form->getElement('group_id');
        $this->assertContainsOptionLabelRecursive(__('General'), $element->getValues());
        $this->assertContainsOptionLabelRecursive(__('Wholesale'), $element->getValues());
        $this->assertContainsOptionLabelRecursive(__('Retailer'), $element->getValues());
    }

    private function assertContainsOptionLabelRecursive($label, array $values)
    {
        $this->assertTrue($this->hasOptionLabelRecursive($label, $values), 'Label ' . $label . ' not found');
    }

    private function hasOptionLabelRecursive($label, array $values)
    {
        $hasLabel = false;
        foreach ($values as $option) {
            $this->assertArrayHasKey('label', $option);
            $this->assertArrayHasKey('value', $option);
            if (strpos((string)$option['label'], (string)$label) !== false) {
                $hasLabel = true;
                break;
            } elseif (is_array($option['value'])) {
                $hasLabel |= $this->hasOptionLabelRecursive($label, $option['value']);
            }
        }

        return (bool)$hasLabel;
    }
}
