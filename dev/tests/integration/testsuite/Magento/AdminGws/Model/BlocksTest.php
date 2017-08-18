<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdminGws\Model;

/**
 * @magentoAppArea adminhtml
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class BlocksTest extends \Magento\TestFramework\TestCase\AbstractController
{
    protected function setUp()
    {
        parent::setUp();
        /** @var $auth \Magento\Backend\Model\Auth */
        $this->_objectManager->get(\Magento\Backend\Model\UrlInterface::class)->turnOffSecretKey();
        $auth = $this->_objectManager->get(\Magento\Backend\Model\Auth::class);
        $auth->login('admingws_user', 'admingws_password1');
    }

    protected function tearDown()
    {
        /** @var $auth \Magento\Backend\Model\Auth */
        $auth = $this->_objectManager->get(\Magento\Backend\Model\Auth::class);
        $auth->logout();
        $this->_objectManager->get(\Magento\Backend\Model\UrlInterface::class)->turnOnSecretKey();
        parent::tearDown();
    }

    /**
     * @magentoConfigFixture default/catalog/magento_catalogpermissions/enabled 1
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     * @magentoDataFixture Magento/AdminGws/_files/role_websites_login.php
     */
    public function testValidateCatalogPermissionsWebsites()
    {
        $this->dispatch('backend/catalog/category/edit/id/3');
        $result = $this->getResponse()->getBody();
        $this->assertContains('category_permissions_3', $result);
        $this->assertContains('limited_website_ids', $result);
    }

    /**
     * @magentoConfigFixture default/catalog/magento_catalogpermissions/enabled 1
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     * @magentoDataFixture Magento/AdminGws/_files/role_stores_login.php
     */
    public function testValidateCatalogPermissionsStoreGroups()
    {
        $this->dispatch('backend/catalog/category/edit/id/3');
        $this->assertRegExp(
            '/title=\\\"New Permission\\\"\s*type=\\\"button\\\"\s*' .
            'class=\\\".*disabled.*\\\"\s*disabled=\\\"disabled\\\"/',
            $this->getResponse()->getBody()
        );
    }

    /**
     * @magentoDataFixture Magento/AdminGws/_files/role_websites_login.php
     */
    public function testBackendUserRoleEditContainsGwsBlock()
    {
        $this->dispatch('backend/admin/user_role/editrole');

        $this->assertInstanceOf(
            \Magento\AdminGws\Block\Adminhtml\Permissions\Tab\Rolesedit\Gws::class,
            \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
                \Magento\Framework\View\LayoutInterface::class
            )->getBlock(
                'adminhtml.user.role.edit.gws'
            ),
            'Magento\AdminGws\Block\Adminhtml\Permissions\Tab\Rolesedit\Gws block is not loaded'
        );

        $body = $this->getResponse()->getBody();
        $this->assertEquals(
            1,
            \Magento\TestFramework\Helper\Xpath::getElementsCountForXpath(
                '//div[contains(@class, "entry-edit") and contains(@class, "form-inline")]'
                . '/fieldset[contains(@class, "fieldset")]/legend[contains(@class, "legend")]'
                . '/span[text() = "Role Scopes"]',
                $body
            )
        );
    }

    /**
     * @magentoDataFixture Magento/AdminGws/_files/role_websites_login.php
     */
    public function testBackendUserRoleEditRoleGridContainsGwsBlock()
    {
        $this->dispatch('backend/admin/user_role/editrolegrid');

        $this->assertInstanceOf(
            \Magento\AdminGws\Block\Adminhtml\Permissions\Grid\Role::class,
            \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
                \Magento\Framework\View\LayoutInterface::class
            )->getBlock(
                'adminhtml.user.role.grid'
            ),
            'Magento\AdminGws\Block\Adminhtml\Permissions\Grid\Role block is not loaded'
        );
    }
}
