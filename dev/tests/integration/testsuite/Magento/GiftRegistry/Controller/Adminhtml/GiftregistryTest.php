<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftRegistry\Controller\Adminhtml;

/**
 * @magentoAppArea adminhtml
 */
class GiftregistryTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    public function testNewAction()
    {
        $this->dispatch('backend/admin/giftregistry/new');
        $this->assertRegExp(
            '/<h1 class\="page-title">\s*New Gift Registry Type\s*<\/h1>/',
            $this->getResponse()->getBody()
        );
        $this->assertContains(
            '<a href="#magento_giftregistry_tabs_general_section_content"' .
            ' id="magento_giftregistry_tabs_general_section" name="general_section"' .
            ' title="General Information"',
            $this->getResponse()->getBody()
        );
        $this->assertContains(
            '<a href="#magento_giftregistry_tabs_registry_attributes_content"' .
            ' id="magento_giftregistry_tabs_registry_attributes"' .
            ' name="registry_attributes" title="Attributes"',
            $this->getResponse()->getBody()
        );
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testSaveAction()
    {
        $this->getRequest()->setPostValue(
            'type',
            ['code' => 'test_registry', 'label' => 'Test', 'sort_order' => 10, 'is_listed' => 1]
        );
        $this->dispatch('backend/admin/giftregistry/save/store/0');
        /** @var $type \Magento\GiftRegistry\Model\Type */
        $type = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\GiftRegistry\Model\Type::class
        );
        $type->setStoreId(0);

        $type = $type->load('test_registry', 'code');

        $this->assertInstanceOf(\Magento\GiftRegistry\Model\Type::class, $type);
        $this->assertNotEmpty($type->getId());
    }
}
