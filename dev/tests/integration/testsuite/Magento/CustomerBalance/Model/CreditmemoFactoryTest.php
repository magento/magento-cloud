<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerBalance\Model;

use Magento\CustomerBalance\Model\Cart\SalesModel\Order;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Tests for CreditmemoFactory class.
 */
class CreditmemoFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * The case when order's (BaseCustomerBalanceInvoiced - BaseCustomerBalanceRefunded) >= creditmemo's BaseGrandTotal
     *
     * @magentoDataFixture Magento/CustomerBalance/_files/invoiced_order_with_customer_balance_and_item.php
     */
    public function testCreateByOrderInvoicedMoreGrandTotal()
    {
        /** @var \Magento\Sales\Model\Order\CreditmemoFactory $creditmemoFactory */
        $creditmemoFactory = $this->objectManager->create(CreditmemoFactory::class);
        /** @var Order $order */
        $order = $this->getOrder();
        $grandTotal = $order->getGrandTotal();
        $baseGrandTotal = $order->getBaseGrandTotal();

        $creditmemo = $creditmemoFactory->createByOrder($order, []);

        $this->assertEquals(
            $baseGrandTotal,
            $creditmemo->getBaseCustomerBalanceAmount()
        );
        $this->assertEquals(
            $grandTotal,
            $creditmemo->getCustomerBalanceAmount()
        );
        $this->assertEquals(
            $baseGrandTotal,
            $creditmemo->getBaseCustomerBalanceReturnMax()
        );
        $this->assertEquals(
            $grandTotal,
            $creditmemo->getCustomerBalanceReturnMax()
        );
        $this->assertTrue($creditmemo->getAllowZeroGrandTotal());
    }

    /**
     * The case when order's (BaseCustomerBalanceInvoiced - BaseCustomerBalanceRefunded) < creditmemo's BaseGrandTotal
     *
     * @magentoDataFixture Magento/CustomerBalance/_files/invoiced_order_with_customer_balance_and_item.php
     */
    public function testCreateByOrderInvoicedLessGrandTotal()
    {
        /** @var \Magento\Sales\Model\Order\CreditmemoFactory $creditmemoFactory */
        $creditmemoFactory = $this->objectManager->create(CreditmemoFactory::class);
        /** @var Order $order */
        $order = $this->getOrder();

        $customerBalanceRefunded = 10;
        $baseCustomerBalanceRefunded = 10;
        $order->setBaseCustomerBalanceRefunded($customerBalanceRefunded);
        $order->setCustomerBalanceRefunded($baseCustomerBalanceRefunded);

        $grandTotal = $order->getGrandTotal();
        $baseGrandTotal = $order->getBaseGrandTotal();

        $creditmemo = $creditmemoFactory->createByOrder($order, []);

        $this->assertEquals(
            $grandTotal - $customerBalanceRefunded,
            $creditmemo->getCustomerBalanceAmount()
        );
        $this->assertEquals(
            $baseGrandTotal - $baseCustomerBalanceRefunded,
            $creditmemo->getBaseCustomerBalanceAmount()
        );
        $this->assertEquals(
            $baseGrandTotal,
            $creditmemo->getBaseCustomerBalanceReturnMax()
        );
        $this->assertEquals(
            $grandTotal,
            $creditmemo->getCustomerBalanceReturnMax()
        );
        $this->assertNull($creditmemo->getAllowZeroGrandTotal());
    }

    /**
     * Get stored order
     *
     * @return Order
     */
    private function getOrder()
    {
        /** @var FilterBuilder $filterBuilder */
        $filterBuilder = $this->objectManager->get(FilterBuilder::class);
        $filters = [
            $filterBuilder->setField(OrderInterface::INCREMENT_ID)
                ->setValue('100000001')
                ->create()
        ];

        /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
        $searchCriteriaBuilder = $this->objectManager->get(SearchCriteriaBuilder::class);
        $searchCriteria = $searchCriteriaBuilder->addFilters($filters)
            ->create();

        $orderRepository = $this->objectManager->get(OrderRepositoryInterface::class);
        $orders = $orderRepository->getList($searchCriteria)
            ->getItems();

        /** @var OrderInterface $order */
        return array_pop($orders);
    }
}
