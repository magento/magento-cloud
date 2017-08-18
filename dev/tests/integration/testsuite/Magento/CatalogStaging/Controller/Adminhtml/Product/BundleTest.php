<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogStaging\Controller\Adminhtml\Product;

/**
 * @magentoAppArea adminhtml
 * @SuppressWarnings(PHPMD.numberOfChildren)
 */
class BundleTest extends \Magento\TestFramework\TestCase\AbstractController
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

    public static function tearDownAfterClass()
    {
        $db = \Magento\TestFramework\Helper\Bootstrap::getInstance()->getBootstrap()
            ->getApplication()
            ->getDbInstance();
        if (!$db->isDbDumpExists()) {
            throw new \LogicException('DB dump does not exist.');
        }
        $db->restoreFromDbDump();
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
     * Test save action
     *
     * @param array $inputData
     * @param string $dispatch
     * @param string $updateName
     * @param string $updateCreatedIn
     *
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/CatalogStaging/_files/bundle_product.php
     * @magentoConfigFixture current_store catalog/frontend/flat_catalog_product 1
     * @dataProvider saveActionDataProvider
     *
     * @return void
     */
    public function testSaveAction(
        $inputData,
        $dispatch,
        $updateName,
        $updateCreatedIn
    ) {
        /** @var $storeManager \Magento\Store\Model\StoreManagerInterface */
        $storeManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Store\Model\StoreManagerInterface::class
        );

        /** @var $store \Magento\Store\Model\Store */
        $store = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Store\Model\Store::class);
        $store->load('fixturestore', 'code');
        $storeManager->setCurrentStore($store->getCode());
        $storeId = $store->getId();

        $dispatch = $dispatch . $storeId;
        $inputData['product']['current_store_id'] = $storeId;

        $this->getRequest()->setPostValue($inputData);
        $this->dispatch($dispatch);

        /** @var $updateRepository \Magento\Staging\Api\UpdateRepositoryInterface */
        $updateRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Staging\Api\UpdateRepositoryInterface::class
        );

        /** @var $update \Magento\Staging\Api\Data\UpdateInterface */
        $update = $updateRepository->get($updateCreatedIn);
        $this->assertEquals($updateName, $update->getName());

        $this->assertContains('You saved this product update.', $this->getResponse()->getBody());
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
        $updateDatetime = new \DateTime('tomorrow');
        $updateDatetime->modify('+10 day');
        $updateStartTime = $updateDatetime->format('m/d/Y') . ' 12:00 am';
        $updateBundleProductName = 'Bundle Updated';
        $updateCreatedIn = strtotime($updateStartTime);
        $updateName = 'New update';
        $bundleOptionUpdatedName = 'Bundle Product Items - updated';

        return [
            'Update Save' => [
                [
                    'product' => [
                        'current_store_id' => 0,
                        'status' => '1',
                        'name' => $updateBundleProductName,
                        'sku' => 'bundle-product',
                        'tax_class_id' => '2',
                        'quantity_and_stock_status' => [
                            'is_in_stock' => '1',
                            'qty' => '0',
                        ],
                        'category_ids' => [
                            '2',
                        ],
                        'visibility' => '4',
                        'price_type' => '0',
                        'is_returnable' => '2',
                        'url_key' => 'bundle-product',
                        'meta_title' => 'Bundle Product',
                        'meta_keyword' => 'Bundle Product',
                        'meta_description' => 'Bundle Product',
                        'price_view' => '0',
                        'options_container' => 'container2',
                        'gift_wrapping_price' => '0.00',
                        'stock_data' => [
                            'item_id' => '3',
                            'product_id' => '3',
                            'stock_id' => '1',
                            'qty' => '0.0000',
                            'min_qty' => '0',
                            'use_config_min_qty' => '1',
                            'is_qty_decimal' => '0',
                            'backorders' => '0',
                            'use_config_backorders' => '1',
                            'min_sale_qty' => '1',
                            'use_config_min_sale_qty' => '1',
                            'max_sale_qty' => '10000',
                            'use_config_max_sale_qty' => '1',
                            'is_in_stock' => '1',
                            'notify_stock_qty' => '1',
                            'use_config_notify_stock_qty' => '1',
                            'manage_stock' => '1',
                            'use_config_manage_stock' => '1',
                            'stock_status_changed_auto' => '0',
                            'use_config_qty_increments' => '1',
                            'qty_increments' => '1',
                            'use_config_enable_qty_inc' => '0',
                            'enable_qty_increments' => '0',
                            'is_decimal_divided' => '0',
                            'deferred_stock_update' => '1',
                            'use_config_deferred_stock_update' => '1',
                            'type_id' => 'bundle',
                        ],
                        'attribute_set_id' => '4',
                        'use_config_is_returnable' => '1',
                        'gift_message_available' => '0',
                        'use_config_gift_message_available' => '1',
                        'current_product_id' => '3',
                        'gift_wrapping_available' => '1',
                        'use_config_gift_wrapping_available' => '1',
                        'affect_product_custom_options' => '1',
                        'is_new' => '0',
                        'price' => '',
                        'weight' => '',
                        'product_has_weight' => '1',
                        'sku_type' => '0',
                        'weight_type' => '0',
                        'description' => 'Description with <b> html tag </b>',
                        'short_description' => 'Bundle',
                        'url_key_create_redirect' => '',
                        'special_price' => '',
                        'shipment_type' => '0',
                    ],
                    'bundle_options' => [
                        'bundle_options' => [
                            [
                                'position' => '1',
                                'option_id' => '3',
                                'title' => $bundleOptionUpdatedName,
                                'type' => 'select',
                                'required' => '1',
                                'bundle_selections' => [
                                    [
                                        'selection_id' => '3',
                                        'option_id' => '3',
                                        'product_id' => '1',
                                        'name' => 'simple',
                                        'sku' => 'simple',
                                        'is_default' => '1',
                                        'selection_price_value' => '0.00',
                                        'selection_price_type' => '0',
                                        'selection_qty' => '1.0000',
                                        'selection_can_change_qty' => '1',
                                        'position' => '1',
                                        'record_id' => '0',
                                        'id' => '1',
                                        'delete' => '',
                                    ],
                                ],
                                'record_id' => '0',
                                'delete' => '',
                                'bundle_button_proxy' => [
                                    [
                                        'entity_id' => '1',
                                        'name' => 'simple',
                                        'sku' => 'simple',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'staging' => [
                        'mode' => 'save',
                        'update_id' => null,
                        'name' => $updateName,
                        'description' => 'Description',
                        'start_time' => $updateStartTime,
                        'end_time' => null,
                        'select_id' => null,
                    ],
                    'affect_bundle_product_selections' => '1'
                ],
                'backend/catalogstaging/product/save/id/3/type/bundle/store/',
                $updateName,
                $updateCreatedIn
            ],
        ];
    }
}
