<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogEvent\Controller\Adminhtml\Catalog;

/**
 * @magentoAppArea adminhtml
 */
class EventTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    public function testEditActionSingleStore()
    {
        $this->dispatch('backend/admin/catalog_event/new');
        $body = $this->getResponse()->getBody();
        $this->assertNotContains('name="store_switcher"', $body);
    }

    /**
     * @magentoDataFixture Magento/Store/_files/core_fixturestore.php
     * @magentoDataFixture Magento/CatalogEvent/_files/events.php
     * @magentoConfigFixture current_store catalog/frontend/flat_catalog_product 1
     */
    public function testEditActionMultipleStore()
    {
        /** @var $event \Magento\CatalogEvent\Model\Event */
        $event = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CatalogEvent\Model\Event::class
        );
        $event->load(\Magento\CatalogEvent\Model\Event::DISPLAY_CATEGORY_PAGE, 'display_state');
        $this->dispatch('backend/admin/catalog_event/edit/id/' . $event->getId());
        $body = $this->getResponse()->getBody();

        $this->assertContains('name="store_switcher"', $body);
        $event->delete();
        unset($event);
    }
}
