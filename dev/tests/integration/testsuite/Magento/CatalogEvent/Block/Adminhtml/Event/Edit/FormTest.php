<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogEvent\Block\Adminhtml\Event\Edit;

/**
 * Test class for \Magento\CatalogEvent\Block\Adminhtml\Event\Edit\Form
 * @magentoAppArea adminhtml
 */
class FormTest extends \PHPUnit_Framework_TestCase
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
        /** @var $event \Magento\CatalogEvent\Model\Event */
        $event = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\CatalogEvent\Model\Event'
        );
        $event->setCategoryId(1)->setId(1);
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $objectManager->get('Magento\Framework\Registry')->register('magento_catalogevent_event', $event);
        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        )->createBlock(
            'Magento\CatalogEvent\Block\Adminhtml\Event\Edit\Form'
        );
        $prepareFormMethod = new \ReflectionMethod(
            'Magento\CatalogEvent\Block\Adminhtml\Event\Edit\Form',
            '_prepareForm'
        );
        $prepareFormMethod->setAccessible(true);
        $prepareFormMethod->invoke($block);

        $form = $block->getForm();
        foreach (['date_start', 'date_end'] as $id) {
            $element = $form->getElement($id);
            $this->assertNotNull($element);
            $this->assertNotEmpty($element->getDateFormat());
            $this->assertNotEmpty($element->getTimeFormat());
        }
    }
}
