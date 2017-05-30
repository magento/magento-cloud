<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdminGws\Model;

use Magento\TestFramework\Helper\Bootstrap;

/**
 * @magentoAppArea adminhtml
 */
class CollectionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $_collections;

    /**
     * @var Magento\Authorization\Model\Role
     */
    private $_role;

    /**
     * @var Magento\AdminGws\Model\Role
     */
    private $_adminGwsRole;

    protected function setUp()
    {
        /** @var \Magento\Framework\ObjectManagerInterface $objectManager */
        $objectManager = Bootstrap::getObjectManager();
        $this->_collections = [
            'invoice' => $objectManager
                ->create(\Magento\Sales\Model\ResourceModel\Order\Invoice\Grid\Collection::class),
            'shipment' => $objectManager
                ->create(\Magento\Sales\Model\ResourceModel\Order\Shipment\Grid\Collection::class),
            'creditmemo' => $objectManager
                ->create(\Magento\Sales\Model\ResourceModel\Order\Creditmemo\Grid\Collection::class)
        ];
        $this->_role = Bootstrap::getObjectManager()->create(
            \Magento\Authorization\Model\Role::class
        )
            ->load('admingws_role','role_name');
        $this->_adminGwsRole = Bootstrap::getObjectManager()
            ->get(\Magento\AdminGws\Model\Role::class)
            ->setAdminRole($this->_role);
    }

    protected function tearDown()
    {
        $role = Bootstrap::getObjectManager()
            ->create(\Magento\Authorization\Model\Role::class)
            ->load('admingws_admin','role_name');
        Bootstrap::getObjectManager()->get(\Magento\AdminGws\Model\Role::class)->setAdminRole($role);

        parent::tearDown();
    }


    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order_invoice_shipment_creditmemo_for_two_stores.php
     * @magentoDataFixture Magento/AdminGws/_files/role_sales_data.php
     */
    public function testValidateSalesCollectionsPerStore()
    {
        foreach ($this->_collections as $collection) {
            $this->assertEquals(1, $collection->count());
        }
    }
}
