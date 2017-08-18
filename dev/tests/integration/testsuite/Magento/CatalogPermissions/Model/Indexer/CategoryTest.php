<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogPermissions\Model\Indexer;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\CatalogPermissions\Model\Permission;

/**
 * @magentoDbIsolation disabled
 * @magentoAppIsolation enabled
 */
class CategoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Permissions index resource
     *
     * @var \Magento\CatalogPermissions\Model\ResourceModel\Permission\Index
     */
    protected $permissionIndex;

    /**
     * Category model
     *
     * @var \Magento\Catalog\Model\Category
     */
    protected $category;

    /**
     * Object manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Test setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->permissionIndex = $this->objectManager->create(
            \Magento\CatalogPermissions\Model\ResourceModel\Permission\Index::class
        );
        $this->category = $this->objectManager->create(\Magento\Catalog\Model\Category::class);
    }

    /**
     * Test reindex all
     *
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     * @magentoDataFixture Magento/CatalogPermissions/_files/permission.php
     *
     * @return void
     */
    public function testReindexAll()
    {
        /** @var  $indexer \Magento\Framework\Indexer\IndexerInterface */
        $indexer = $this->objectManager->create(\Magento\Indexer\Model\Indexer::class);
        $indexer->load(\Magento\CatalogPermissions\Model\Indexer\Category::INDEXER_ID);
        $indexer->reindexAll();

        $this->assertEmpty($this->permissionIndex->getIndexForCategory(10));
        $this->assertContains($this->getCategoryDataById(6), $this->permissionIndex->getIndexForCategory(6));
    }

    /**
     * Test reindex row
     *
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     * @magentoDataFixture Magento/CatalogPermissions/_files/additional_permissions.php
     *
     * @return void
     */
    public function testReindexRow()
    {
        /** @var  $indexer \Magento\Framework\Indexer\IndexerInterface */
        $indexer = $this->objectManager->create(\Magento\Indexer\Model\Indexer::class);
        $indexer->load(\Magento\CatalogPermissions\Model\Indexer\Category::INDEXER_ID);
        $indexer->reindexAll();

        $this->assertNotEmpty($this->permissionIndex->getIndexForCategory(4));

        $permissionId =
            $this->objectManager->create(\Magento\CatalogPermissions\Model\ResourceModel\Permission\Collection::class)
            ->getAllIds()[0];

        /** @var Permission $permission */
        $permission = $this->objectManager->create(\Magento\CatalogPermissions\Model\Permission::class);
        $permission->load($permissionId);
        $permission->delete();

        $indexer->reindexRow(3);
        $this->assertEmpty($this->permissionIndex->getIndexForCategory(4));
    }

    /**
     * Test moving category
     *
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     * @magentoDataFixture Magento/CatalogPermissions/_files/permission.php
     *
     * @return void
     */
    public function testCategoryMove()
    {
        $this->category->load(7);
        $this->category->move(6, null);

        $this->assertContains($this->getCategoryDataById(7), $this->permissionIndex->getIndexForCategory(7));
    }

    /**
     * Test category creation
     *
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     * @magentoDataFixture Magento/CatalogPermissions/_files/permission.php
     *
     * @return void
     */
    public function testCategoryCreate()
    {
        $this->category->isObjectNew(true);
        $this->category->setId(13)
            ->setName('New')
            ->setParentId(6)
            ->setPath('1/2/6/13')
            ->setLevel(3)
            ->setAvailableSortBy('name')
            ->setDefaultSortBy('name')
            ->setIsActive(true)
            ->setPosition(3)
            ->save();

        $this->assertContains($this->getCategoryDataById(13), $this->permissionIndex->getIndexForCategory(13));
    }

    /**
     * Test correct permissions inheritance. Parent category has permission for some customer group.
     * Permissions for child category are set for all customer groups.
     * We check if child category has correct permissions after reindex.
     *
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_category_view 1
     * @magentoDataFixture Magento/Catalog/_files/categories_no_products.php
     * @return void
     */
    public function testPermissionsInheritanceForAllCustomerGroups()
    {
        $parentCategoryId = 3;
        $childCategoryId = 4;
        $notLoggedInCustomerGroupId = 0;

        /** @var $permission Permission */
        $permission = $this->objectManager->create(\Magento\CatalogPermissions\Model\Permission::class);
        $websiteId = $this->objectManager->get(
            \Magento\Store\Model\StoreManagerInterface::class
        )->getWebsite()->getId();
        $permission->setWebsiteId($websiteId)
            ->setCategoryId($parentCategoryId)
            ->setCustomerGroupId($notLoggedInCustomerGroupId)
            ->setGrantCatalogCategoryView(Permission::PERMISSION_ALLOW)
            ->save();
        $permission->setWebsiteId($websiteId)
            ->setCategoryId($childCategoryId)
            ->setCustomerGroupId(null)
            ->setGrantCatalogCategoryView(Permission::PERMISSION_DENY)
            ->save();

        /** @var  $indexer \Magento\Framework\Indexer\IndexerInterface */
        $indexer = $this->objectManager->create(\Magento\Indexer\Model\Indexer::class);
        $indexer->load(\Magento\CatalogPermissions\Model\Indexer\Category::INDEXER_ID);
        $indexer->reindexAll();

        $indexedPermission = $this->permissionIndex->getIndexForCategory(
            $childCategoryId,
            $notLoggedInCustomerGroupId,
            $websiteId
        );

        $this->assertEquals(
            Permission::PERMISSION_DENY,
            $indexedPermission[$childCategoryId]['grant_catalog_category_view']
        );
    }

    /**
     * Test overriding parent category permissions in child category
     *
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_category_view 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_product_price 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_checkout_items 1
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     *
     * @return void
     */
    public function testOverrideParentCategoryPermissions()
    {
        /** @var $permission Permission */
        $permission = $this->objectManager->create(\Magento\CatalogPermissions\Model\Permission::class);
        $websiteId = $this->objectManager->get(
            \Magento\Store\Model\StoreManagerInterface::class
        )->getWebsite()->getId();
        $permission->setWebsiteId($websiteId)
            ->setCategoryId(6)
            ->setCustomerGroupId(null)
            ->setGrantCatalogCategoryView(Permission::PERMISSION_DENY)
            ->setGrantCatalogProductPrice(Permission::PERMISSION_DENY)
            ->setGrantCheckoutItems(Permission::PERMISSION_DENY)
            ->save();

        $this->category->isObjectNew(true);
        $this->category->setId(13)
            ->setName('New')
            ->setParentId(6)
            ->setPath('1/2/6/13')
            ->setLevel(3)
            ->setAvailableSortBy('name')
            ->setDefaultSortBy('name')
            ->setIsActive(true)
            ->setPosition(3)
            ->save();

        /** @var $permission Permission */
        $permission = $this->objectManager->create(\Magento\CatalogPermissions\Model\Permission::class);
        $permission->setWebsiteId($websiteId)
            ->setCategoryId(13)
            ->setCustomerGroupId(1)
            ->setGrantCatalogCategoryView(Permission::PERMISSION_ALLOW)
            ->setGrantCatalogProductPrice(Permission::PERMISSION_ALLOW)
            ->setGrantCheckoutItems(Permission::PERMISSION_ALLOW)
            ->save();

        /** @var  $indexer \Magento\Framework\Indexer\IndexerInterface */
        $indexer = $this->objectManager->create(\Magento\Indexer\Model\Indexer::class);
        $indexer->load(\Magento\CatalogPermissions\Model\Indexer\Category::INDEXER_ID);
        $indexer->reindexAll();
        foreach ($this->getExpectedCategoryPermissionsOverridingMap() as $categoryId => $permissions) {
            foreach ($permissions as $groupId => $permissionRules) {
                $indexedValue = $this->permissionIndex->getIndexForCategory($categoryId, $groupId)[0];
                foreach ($permissionRules as $ruleKey => $ruleValue) {
                    $this->assertEquals($ruleValue, (int)$indexedValue[$ruleKey]);
                }
            }
        }
    }

    /**
     * Get category permissions
     *
     * @return array
     */
    protected function getExpectedCategoryPermissionsOverridingMap()
    {
        return [
            '6' => [
                0 => [
                    'grant_catalog_category_view' => Permission::PERMISSION_DENY,
                    'grant_catalog_product_price' => Permission::PERMISSION_DENY,
                    'grant_checkout_items' => Permission::PERMISSION_DENY,
                ],
                1 => [
                    'grant_catalog_category_view' => Permission::PERMISSION_DENY,
                    'grant_catalog_product_price' => Permission::PERMISSION_DENY,
                    'grant_checkout_items' => Permission::PERMISSION_DENY,
                ],
                2 => [
                    'grant_catalog_category_view' => Permission::PERMISSION_DENY,
                    'grant_catalog_product_price' => Permission::PERMISSION_DENY,
                    'grant_checkout_items' => Permission::PERMISSION_DENY,
                ],
                3 => [
                    'grant_catalog_category_view' => Permission::PERMISSION_DENY,
                    'grant_catalog_product_price' => Permission::PERMISSION_DENY,
                    'grant_checkout_items' => Permission::PERMISSION_DENY,
                ],
            ],
            '13' => [
                0 => [
                    'grant_catalog_category_view' => Permission::PERMISSION_DENY,
                    'grant_catalog_product_price' => Permission::PERMISSION_DENY,
                    'grant_checkout_items' => Permission::PERMISSION_DENY,
                ],
                1 => [
                    'grant_catalog_category_view' => Permission::PERMISSION_ALLOW,
                    'grant_catalog_product_price' => Permission::PERMISSION_ALLOW,
                    'grant_checkout_items' => Permission::PERMISSION_ALLOW,
                ],
                2 => [
                    'grant_catalog_category_view' => Permission::PERMISSION_DENY,
                    'grant_catalog_product_price' => Permission::PERMISSION_DENY,
                    'grant_checkout_items' => Permission::PERMISSION_DENY,
                ],
                3 => [
                    'grant_catalog_category_view' => Permission::PERMISSION_DENY,
                    'grant_catalog_product_price' => Permission::PERMISSION_DENY,
                    'grant_checkout_items' => Permission::PERMISSION_DENY,
                ],
            ]
        ];
    }

    /**
     * Return default row from permission by category id
     *
     * @param int $id
     *
     * @return array
     */
    protected function getCategoryDataById($id)
    {
        return [
            'category_id' => $id,
            'website_id' => '1',
            'customer_group_id' => '1',
            'grant_catalog_category_view' => '-2',
            'grant_catalog_product_price' => '-2',
            'grant_checkout_items' => '-2'
        ];
    }
}
