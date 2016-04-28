<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerCustomAttributes\Block\Adminhtml\Customer\Attribute\Edit\Tab;

/**
 * Test class for \Magento\CustomerCustomAttributes\Block\Adminhtml\Customer\Attribute\Edit\Tab\Main
 *
 * @magentoAppArea adminhtml
 */
class MainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoAppIsolation enabled
     */
    public function testPrepareForm()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\DesignInterface'
        )->setArea(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE
        )->setDefaultDesignTheme();
        $entityType = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Eav\Model\Config'
        )->getEntityType(
            'customer'
        );
        $model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Customer\Model\Attribute'
        );
        $model->setEntityTypeId($entityType->getId());
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $objectManager->get('Magento\Framework\Registry')->register('entity_attribute', $model);

        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        )->createBlock(
            'Magento\CustomerCustomAttributes\Block\Adminhtml\Customer\Attribute\Edit\Tab\Main'
        );
        $prepareFormMethod = new \ReflectionMethod(
            'Magento\CustomerCustomAttributes\Block\Adminhtml\Customer\Attribute\Edit\Tab\Main',
            '_prepareForm'
        );
        $prepareFormMethod->setAccessible(true);
        $prepareFormMethod->invoke($block);

        $form = $block->getForm();
        foreach (['date_range_min', 'date_range_max', 'is_required', 'default_value_text', 'is_visible'] as $id) {
            $element = $form->getElement($id);
            $this->assertNotNull($element);
            if (in_array($id, ['date_range_min', 'date_range_max'])) {
                $this->assertNotEmpty($element->getDateFormat());
            }
        }
    }
}
