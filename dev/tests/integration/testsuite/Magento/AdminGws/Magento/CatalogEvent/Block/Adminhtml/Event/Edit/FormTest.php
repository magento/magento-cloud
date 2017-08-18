<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdminGws\Magento\CatalogEvent\Block\Adminhtml\Event\Edit;

/**
 * Test that Catalog Event Edit form is wrapped by AdminGws
 *
 * @magentoAppArea adminhtml
 */
class FormTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoDataFixture Magento/AdminGws/_files/role_websites_login.php
     * @magentoDataFixture Magento/CatalogEvent/_files/events.php
     */
    public function testToHtmlDisabledTickerDisplay()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\Authorization\Model\Role $adminRole */
        $adminRole = $objectManager->get(\Magento\Authorization\Model\Role::class);
        $adminRole->load('admingws_role', 'role_name');

        /** @var \Magento\AdminGws\Model\Role $adminGwsRole */
        $adminGwsRole = $objectManager->get(\Magento\AdminGws\Model\Role::class);
        $adminGwsRole->setAdminRole($adminRole);

        /** @var $event \Magento\CatalogEvent\Model\Event */
        $event = $objectManager->create(\Magento\CatalogEvent\Model\Event::class);
        $event->load(1, 'category_id');
        $objectManager->get(\Magento\Framework\Registry::class)->register('magento_catalogevent_event', $event);

        /** @var \Magento\CatalogEvent\Block\Adminhtml\Event\Edit\Form $block */
        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        )->createBlock(
            \Magento\CatalogEvent\Block\Adminhtml\Event\Edit\Form::class
        );
        $block->toHtml();

        $checkboxValues = [
            \Magento\CatalogEvent\Model\Event::DISPLAY_CATEGORY_PAGE,
            \Magento\CatalogEvent\Model\Event::DISPLAY_PRODUCT_PAGE,
        ];
        /** @var \Magento\Framework\Data\Form\Element\AbstractElement $element */
        $element = $block->getForm()->getElement('display_state_array');
        foreach ($checkboxValues as $value) {
            $this->assertEquals('disabled', $element->getDisabled($value));
        }
    }
}
