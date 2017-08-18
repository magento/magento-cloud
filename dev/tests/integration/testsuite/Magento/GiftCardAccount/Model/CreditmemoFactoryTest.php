<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardAccount\Model;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Test for CreditmemoFactory class.
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
     * The case when order's (BaseGiftCardsInvoiced - BaseGiftCardsRefunded) <  creditmemo's BaseGrandTotal
     *
     * @magentoDataFixture Magento/GiftCardAccount/_files/invoiced_order_with_gift_card_account_and_item.php
     */
    public function testCreateByOrderInvoicedMoreGrandTotal()
    {
        /** @var \Magento\Sales\Model\Order\CreditmemoFactory $creditmemoFactory */
        $creditmemoFactory = $this->objectManager->create(CreditmemoFactory::class);
        /** @var Order $order */
        $order = $this->getOrder();
        $creditmemo = $creditmemoFactory->createByOrder($order, []);

        $this->assertEquals(
            $order->getBaseGiftCardsInvoiced() - $order->getBaseGiftCardsRefunded(),
            $creditmemo->getBaseGiftCardsAmount()
        );
        $this->assertEquals(
            $order->getGiftCardsInvoiced() - $order->getGiftCardsRefunded(),
            $creditmemo->getGiftCardsAmount()
        );
    }

    /**
     * The case when order's (BaseGiftCardsInvoiced - BaseGiftCardsRefunded) >=  creditmemo's BaseGrandTotal
     *
     * @magentoDataFixture Magento/GiftCardAccount/_files/invoiced_order_with_gift_card_account_and_item.php
     */
    public function testCreateByOrderInvoicedLessGrandTotal()
    {
        $giftCardsRefunded = 0;
        //Sum of all items grand totals from the fixture(1 item with grand total 100: 1*100=100)
        $itemsGrandTotal = 100;
        /** @var \Magento\Sales\Model\Order\CreditmemoFactory $creditmemoFactory */
        $creditmemoFactory = $this->objectManager->create(CreditmemoFactory::class);
        /** @var Order $order */
        $order = $this->getOrder();
        //Override refunded amount
        $order->setBaseGiftCardsRefunded($giftCardsRefunded);
        $order->setGiftCardsRefunded($giftCardsRefunded);
        $creditmemo = $creditmemoFactory->createByOrder($order, []);

        $this->assertEquals(
            $itemsGrandTotal,
            $creditmemo->getBaseGiftCardsAmount()
        );
        $this->assertEquals(
            $itemsGrandTotal,
            $creditmemo->getGiftCardsAmount()
        );
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
