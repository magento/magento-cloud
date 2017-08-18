<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Invitation\Model\ResourceModel\Report\Invitation\Order\Initial;

/**
 * Reports invitation order report collection test
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class CollectionTest extends \PHPUnit\Framework\TestCase
{

    /** @var \Magento\Invitation\Model\ResourceModel\Report\Invitation\Order\Collection */
    protected $_model;

    protected function setUp()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(\Magento\Invitation\Model\ResourceModel\Report\Invitation\Order\Collection::class);
        $this->_model->setDateRange('2011-08-03', '2040-08-03');
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoDataFixture Magento/Sales/_files/order_with_customer.php
     * @magentoDataFixture Magento/Invitation/_files/invitation.php
     */
    public function testInvitationReportOneOrderOneCustomer()
    {
        $items = $this->_model->getItems();
        $item = $items[0]->getData();
        $this->assertEquals(100, $item['purchased_rate']);
        $this->assertEquals(100, $item['accepted_rate']);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/two_orders_for_one_of_two_customers.php
     * @magentoDataFixture Magento/Invitation/_files/invitation.php
     */
    public function testInvitationReportTwoOrdersOneCustomer()
    {
        $items = $this->_model->getItems();
        $item = $items[0]->getData();
        $this->assertEquals(100, $item['purchased_rate']);
        $this->assertEquals(100, $item['accepted_rate']);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/two_orders_for_one_of_two_customers.php
     * @magentoDataFixture Magento/Invitation/_files/two_invitations.php
     */
    public function testInvitationReportTwoOrdersTwoCustomers()
    {
        $items = $this->_model->getItems();
        $item = $items[0]->getData();
        $this->assertEquals(50, $item['purchased_rate']);
        $this->assertEquals(100, $item['accepted_rate']);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/two_orders_for_two_diff_customers.php
     * @magentoDataFixture Magento/Invitation/_files/two_invitations.php
     */
    public function testInvitationReportTwoOrdersDiffTwoCustomers()
    {
        $items = $this->_model->getItems();
        $item = $items[0]->getData();
        $this->assertEquals(100, $item['purchased_rate']);
        $this->assertEquals(100, $item['accepted_rate']);
    }
}
