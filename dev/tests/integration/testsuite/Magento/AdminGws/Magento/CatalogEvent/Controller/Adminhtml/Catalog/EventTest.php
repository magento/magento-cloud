<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdminGws\Magento\CatalogEvent\Controller\Adminhtml\Catalog;

/**
 * Test that CatalogEvent controller is wrapped by AdminGws
 *
 * @magentoAppArea adminhtml
 * @magentoDataFixture Magento/AdminGws/_files/role_websites_login.php
 */
class EventTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * Get credentials to login restricted admin user
     *
     * @return array
     */
    protected function _getAdminCredentials()
    {
        return ['user' => 'admingws_user', 'password' => 'admingws_password1'];
    }

    public function testIndexActionRestrictedUserCanSeeGrid()
    {
        $this->dispatch('backend/admin/catalog_event/index/');
        $body = $this->getResponse()->getBody();

        $this->assertContains('Events', $body);
        $this->assertTag(['tag' => 'table', 'id' => 'catalogEventGrid_table'], $body, 'Events grid is not found');
    }
}
