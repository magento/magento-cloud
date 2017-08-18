<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogStaging\Controller\Adminhtml;

/**
 * @magentoAppArea adminhtml
 * @SuppressWarnings(PHPMD.numberOfChildren)
 */
class CategoryTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $session;

    /**
     * @var \Magento\Backend\Model\Auth
     */
    protected $auth;

    /**
     * The resource used to authorize action
     *
     * @var string
     */
    protected $resource = null;

    /**
     * The uri at which to access the controller
     *
     * @var string
     */
    protected $uri = null;

    /**
     * SetUp restoreFromDbDump
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        \Magento\TestFramework\Helper\Bootstrap::getInstance()->getBootstrap()
            ->getApplication()
            ->getDbInstance()
            ->restoreFromDbDump();
    }

    /**
     * SetUp
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_objectManager->get(\Magento\Backend\Model\UrlInterface::class)->turnOffSecretKey();

        $this->auth = $this->_objectManager->get(\Magento\Backend\Model\Auth::class);
        $this->session = $this->auth->getAuthStorage();
        $credentials = $this->getAdminCredentials();
        $this->auth->login($credentials['user'], $credentials['password']);
        $this->_objectManager->get(\Magento\Security\Model\Plugin\Auth::class)->afterLogin($this->auth);
    }

    /**
     * Get credentials to login admin user
     *
     * @return array
     */
    protected function getAdminCredentials()
    {
        return [
            'user' => \Magento\TestFramework\Bootstrap::ADMIN_NAME,
            'password' => \Magento\TestFramework\Bootstrap::ADMIN_PASSWORD
        ];
    }

    /**
     * TearDown
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->auth->getAuthStorage()->destroy(['send_expire_cookie' => false]);
        $this->auth = null;
        $this->session = null;
        $this->_objectManager->get(\Magento\Backend\Model\UrlInterface::class)->turnOnSecretKey();
        parent::tearDown();
    }

    /**
     * Utilize backend session model by default
     *
     * @param \PHPUnit\Framework\Constraint\Constraint $constraint
     * @param string|null $messageType
     * @param string $messageManagerClass
     * @return void
     */
    public function assertSessionMessages(
        \PHPUnit\Framework\Constraint\Constraint $constraint,
        $messageType = null,
        $messageManagerClass = \Magento\Framework\Message\Manager::class
    ) {
        parent::assertSessionMessages($constraint, $messageType, $messageManagerClass);
    }

    /**
     * Test save action
     *
     * @param array $inputData
     * @param array $inputDataForUpdateChanging
     * @param string $dispatch
     * @param string $categoryName
     * @param int $createdIn
     * @param string $updateName
     * @param string $secondUpdateCategoryName
     * @param string $secondUpdateName
     *
     * @magentoDataFixture Magento/Store/_files/core_fixturestore.php
     * @magentoDbIsolation enabled
     * @magentoConfigFixture current_store catalog/frontend/flat_catalog_product 1
     * @dataProvider saveActionDataProvider
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testSaveAction(
        $inputData,
        $inputDataForUpdateChanging,
        $dispatch,
        $categoryName,
        $createdIn,
        $updateName,
        $secondUpdateCategoryName,
        $secondUpdateName
    ) {
        $this->markTestSkipped('MAGETWO-54657');
        /** @var $storeManager \Magento\Store\Model\StoreManagerInterface */
        $storeManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Store\Model\StoreManagerInterface::class
        );

        //Change and check category update values in 'fixturestore' store

        /** @var $store \Magento\Store\Model\Store */
        $store = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Store\Model\Store::class);
        $store->load('fixturestore', 'code');
        $storeId = $store->getId();

        /** @var $category \Magento\Catalog\Model\Category */
        $category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Catalog\Model\Category::class
        );
        $store = $storeManager->getStore($storeId);
        $storeManager->setCurrentStore($store->getCode());
        $category->load(2);

        $inputData['row_id'] = $category->getData('row_id');
        $inputData['store_id'] = $storeId;

        $this->getRequest()->setPostValue($inputData);
        $this->getRequest()->setParam('store', $storeId);

        $postValueBeforeSave = $this->getRequest()->getPostValue();

        //Create new category update in 'fixturestore' store
        $this->dispatch($dispatch);

        $postValueAfterSave = $this->getRequest()->getPostValue();

        /** @var $category \Magento\Catalog\Model\Category */
        $category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Catalog\Model\Category::class
        );
        $store = $storeManager->getStore($storeId);
        $storeManager->setCurrentStore($store->getCode());
        $category->setStoreId($storeId)->load(2);
        $this->assertEquals($categoryName, $category->getData('name'));
        $this->assertEquals($createdIn, $category->getData('created_in'));

        $inputDataForUpdateChanging['row_id'] = $category->getData('row_id');
        $inputDataForUpdateChanging['store_id'] = $storeId;

        /** @var $update \Magento\Staging\Model\Update */
        $update = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Staging\Model\Update::class
        );
        $update->load($createdIn);
        $this->assertEquals($updateName, $update->getData('name'));
        $this->assertEquals($postValueBeforeSave, $postValueAfterSave);

        $this->getRequest()->setDispatched(false);

        $this->getRequest()->setPostValue($inputDataForUpdateChanging);
        $this->getRequest()->setParam('store', $storeId);

        $postValueBeforeSave = $this->getRequest()->getPostValue();

        //Change category update in 'fixturestore' store
        $this->dispatch($dispatch);

        $postValueAfterSave = $this->getRequest()->getPostValue();

        /** @var $category \Magento\Catalog\Model\Category */
        $category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Catalog\Model\Category::class
        );
        $store = $storeManager->getStore($storeId);
        $storeManager->setCurrentStore($store->getCode());
        $category->load(2);
        $this->assertEquals($secondUpdateCategoryName, $category->getData('name'));
        $this->assertEquals($createdIn, $category->getData('created_in'));

        /** @var $update \Magento\Staging\Model\Update */
        $update = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Staging\Model\Update::class
        );
        $update->load($createdIn);
        $this->assertEquals($secondUpdateName, $update->getData('name'));
        $this->assertEquals($postValueBeforeSave, $postValueAfterSave);

        //Check last category update values in 'default' store

        /** @var $store \Magento\Store\Model\Store */
        $store = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Store\Model\Store::class);
        $store->load('default', 'code');
        $defaultStoreId = $store->getId();

        /** @var $category \Magento\Catalog\Model\Category */
        $category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Catalog\Model\Category::class
        );
        $store = $storeManager->getStore($defaultStoreId);
        $storeManager->setCurrentStore($store->getCode());
        $category->load(2);

        $this->assertEquals('Default Category', $category->getData('name'));
        $this->assertEquals($createdIn, $category->getData('created_in'));
    }

    /**
     * Save action DataProvider
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @return array
     */
    public function saveActionDataProvider()
    {
        //Data for first update
        $updateDatetime = new \DateTime('tomorrow');
        $updateDatetime->modify('+10 day');
        $updateStartTime = $updateDatetime->format('m/d/Y') . ' 12:00 am';
        $updateCategoryName = 'Default Category First Update';
        $updateCreatedIn = strtotime($updateStartTime);
        $updateName = 'New update';

        //Data for second update
        $secondUpdateCategoryName = 'Default Category Second Update';
        $secondUpdateName = 'Second update';

        return [
            'Update Save' => [
                [
                    'store_id' => 0,
                    'row_id' => 2,
                    'entity_id' => 2,
                    'created_in' => 1,
                    'updated_in' => 2147483647,
                    'attribute_set_id' => 3,
                    'parent_id' => 1,
                    'position' => 1,
                    'created_at' => '2016-02-23 10:25:15',
                    'updated_at' => '2016-02-23 10:25:16',
                    'path' => '1/2',
                    'level' => 1,
                    'children_count' => 0,
                    'name' => $updateCategoryName,
                    'display_mode' => 'PRODUCTS',
                    'is_active' => 1,
                    'include_in_menu' => 1,
                    'use_default' => [
                        'url_key' => false
                    ],
                    'use_config' => [
                        'available_sort_by' => true,
                        'default_sort_by' => true,
                        'filter_price_range' => true,
                    ],
                    'staging' => [
                        'mode' => 'save',
                        'update_id' => null,
                        'name' => $updateName,
                        'description' => null,
                        'select_id' => null,
                        'start_time' => $updateStartTime,
                        'end_time' => null,
                    ],
                    'id' => null,
                    'parent' => 0,
                    'savedImage' => [
                        'delete' => false,
                        'value' => null
                    ],
                    'description' => null,
                    'is_anchor' => 1,
                    'default_sort_by' => 'position',
                    'filter_price_range' => null,
                    'meta_title' => null,
                    'meta_keywords' => null,
                    'meta_description' => null,
                    'custom_use_parent_settings' => 0,
                    'custom_layout_update' => null,
                    'custom_apply_to_products' => 0,
                ],
                [
                    'store_id' => 0,
                    'row_id' => 3,
                    'entity_id' => 2,
                    'created_in' => $updateCreatedIn,
                    'updated_in' => 2147483647,
                    'attribute_set_id' => 3,
                    'parent_id' => 1,
                    'position' => 1,
                    'created_at' => '2016-02-23 10:25:15',
                    'updated_at' => '2016-02-23 10:25:16',
                    'path' => '1/2',
                    'level' => 1,
                    'children_count' => 0,
                    'name' => $secondUpdateCategoryName,
                    'display_mode' => 'PRODUCTS',
                    'is_active' => 1,
                    'include_in_menu' => 1,
                    'use_default' => [
                        'url_key' => false
                    ],
                    'use_config' => [
                        'available_sort_by' => true,
                        'default_sort_by' => true,
                        'filter_price_range' => true,
                    ],
                    'staging' => [
                        'mode' => 'save',
                        'update_id' => $updateCreatedIn,
                        'name' => $secondUpdateName,
                        'description' => null,
                        'select_id' => null,
                        'start_time' => $updateStartTime,
                        'end_time' => null,
                    ],
                    'id' => null,
                    'parent' => 0,
                    'savedImage' => [
                        'delete' => false,
                        'value' => null
                    ],
                    'description' => null,
                    'is_anchor' => 1,
                    'default_sort_by' => 'position',
                    'filter_price_range' => null,
                    'meta_title' => null,
                    'meta_keywords' => null,
                    'meta_description' => null,
                    'custom_use_parent_settings' => 0,
                    'custom_layout_update' => null,
                    'custom_apply_to_products' => 0,
                ],
                'backend/catalogstaging/category/update_save',
                $updateCategoryName,
                $updateCreatedIn,
                $updateName,
                $secondUpdateCategoryName,
                $secondUpdateName
            ]
        ];
    }
}
