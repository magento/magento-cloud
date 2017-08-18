<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardAccount\Observer;

use Magento\Framework\Event\Observer;
use Magento\GiftCardAccount\Model\Giftcardaccount;
use Magento\Sales\Block\Adminhtml\Creditmemo;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\GiftCardAccount\Observer\CreditmemoSaveAfter;
use Magento\Framework\DataObject;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\GiftCardAccount\Helper\Data as GiftCardAccountDataHelper;
use Magento\Sales\Model\Order\Status\HistoryFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CreditmemoSaveAfterTest extends \PHPUnit\Framework\TestCase
{
    // GiftCardAccount balance from fixture Magento/GiftCardAccount/_files/order_with_gift_card_account.php
    private static $giftCard1AmountInOrder = 10;

    // GiftCardAccount balance from fixture Magento/GiftCardAccount/_files/order_with_gift_card_account.php
    private static $giftCard2AmountInOrder = 15;

    // Order increment Id from fixture Magento/GiftCardAccount/_files/order_with_gift_card_account.php
    private static $orderIncrementId = 100000001;

    /**
     * @var CreditmemoSaveAfter
     */
    private $creditmemoSaveAfter;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->creditmemoSaveAfter = $this->objectManager->create(CreditmemoSaveAfter::class);
    }

    /**
     * Refund to exist GiftCardAccount.
     * Refund amount sum up with balance from db.
     *
     * @covers \Magento\GiftCardAccount\Observer\CreditmemoSaveAfter::execute
     * @magentoDataFixture Magento/GiftCardAccount/_files/creditmemo_with_gift_card_account.php
     */
    public function testRefundToGiftCardAccountWithExistingAccounts()
    {
        $giftcardAccount = $this->objectManager->create(Giftcardaccount::class);
        $giftCardAccount1Balance = $giftcardAccount->loadByCode('TESTCODE1')->getBalance();
        $giftCardAccount2Balance = $giftcardAccount->loadByCode('TESTCODE2')->getBalance();
        $observer = $this->getObserver();
        $this->creditmemoSaveAfter->execute($observer);

        // e.g. To the account with balance 10 was refunded 20, total 30
        $giftcardAccount->loadByCode('TESTCODE1');
        $this->assertEquals($giftcardAccount->getBalance(), (self::$giftCard1AmountInOrder + $giftCardAccount1Balance));

        // e.g. To the account with balance 25 was refunded 15, total 40
        $giftcardAccount->loadByCode('TESTCODE2');
        $this->assertEquals($giftcardAccount->getBalance(), (self::$giftCard2AmountInOrder + $giftCardAccount2Balance));
    }

    /**
     * Refund if the GiftCardAccounts were deleted.
     * In this case are creating new accounts with the same code and balance from the order.
     *
     * @covers \Magento\GiftCardAccount\Observer\CreditmemoSaveAfter::execute
     * @magentoDataFixture  Magento/GiftCardAccount/_files/creditmemo_with_deleted_gift_card_account.php
     */
    public function testRefundToGiftCardAccountWithDeletedAccounts()
    {
        $giftcardAccount = $this->objectManager->create(Giftcardaccount::class);

        $observer = $this->getObserver();
        $this->creditmemoSaveAfter->execute($observer);

        $giftcardAccount->loadByCode('TESTCODE1');
        $this->assertEquals($giftcardAccount->getBalance(), self::$giftCard1AmountInOrder);

        $giftcardAccount->loadByCode('TESTCODE2');
        $this->assertEquals($giftcardAccount->getBalance(), self::$giftCard2AmountInOrder);
    }

    /**
     * Tests messages added to the order after returns amount to gift card account.
     *
     * @magentoDataFixture  Magento/GiftCardAccount/_files/creditmemo_with_deleted_gift_card_account.php
     */
    public function testComments()
    {
        $observer = $this->getObserver();
        $this->creditmemoSaveAfter->execute($observer);

        $order = $this->getOrder();
        $comments = $order->getAllStatusHistory();
        $realHistoryComments = [];
        foreach ($comments as $comment) {
            $realHistoryComments[] = $comment->getComment();
        }

        $this->assertContains('We refunded $10.00 to Gift Card (TESTCODE1)', $realHistoryComments);
        $this->assertContains('We refunded $15.00 to Gift Card (TESTCODE2)', $realHistoryComments);
    }

    /**
     * Tests messages added to the order after returns amount to Story Credit.
     *
     * @magentoDataFixture  Magento/GiftCardAccount/_files/creditmemo_with_gift_card_account.php
     */
    public function testRefundToStoryCreditComments()
    {
        /** @var Observer $observer*/
        $observer = $this->getObserver();
        /** @var Creditmemo $creditmemo */
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $creditmemo->setCustomerBalanceRefundFlag(true);
        $order = $creditmemo->getOrder();
        $order->setData(OrderInterface::CUSTOMER_IS_GUEST, false);

        $scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSetFlag', 'getValue'])
            ->getMock();
        $scopeConfigMock->method('isSetFlag')
            ->willReturn(true);

        $objectManagerHelper = new ObjectManager($this);
        $creditmemoSaveAfter = $objectManagerHelper->getObject(
            CreditmemoSaveAfter::class,
            [
                'scopeConfig' => $scopeConfigMock,
                'giftCardAccountHelper' => $this->objectManager->create(GiftCardAccountDataHelper::class),
                'historyFactory' => $this->objectManager->create(HistoryFactory::class)
            ]
        );

        $creditmemoSaveAfter->execute($observer);

        $order = $this->getOrder();
        $comments = $order->getAllStatusHistory();
        $realHistoryComments = [];
        foreach ($comments as $comment) {
            $realHistoryComments[] = $comment->getComment();
        }

        $this->assertContains('We refunded $10.00 to Store Credit from Gift Card (TESTCODE1)', $realHistoryComments);
        $this->assertContains('We refunded $15.00 to Store Credit from Gift Card (TESTCODE2)', $realHistoryComments);
    }

    /**
     * Initialize observer for tests.
     *
     * @return Observer
     */
    private function getObserver()
    {
        /** @var Creditmemo $creditmemo */
        $creditmemo = $this->objectManager->get(CreditmemoFactory::class)->createByOrder($this->getOrder());

        /** @var DataObject $event */
        $event = $this->objectManager->create(DataObject::class);
        $event->setCreditmemo($creditmemo);

        /** @var Observer $observer */
        $observer = $this->objectManager->create(Observer::class);
        $observer->setEvent($event);

        return $observer;
    }

    /**
     * Gets order for test
     *
     * @return OrderInterface|mixed
     */
    private function getOrder()
    {
        /** @var FilterBuilder $filterBuilder */
        $filterBuilder = $this->objectManager->get(FilterBuilder::class);
        $filters = [
            $filterBuilder->setField(OrderInterface::INCREMENT_ID)
                ->setValue(self::$orderIncrementId)
                ->create()
        ];

        /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
        $searchCriteriaBuilder = $this->objectManager->get(SearchCriteriaBuilder::class);
        $searchCriteria = $searchCriteriaBuilder->addFilters($filters)->create();

        /** @var  OrderRepositoryInterface $orderRepository */
        $orderRepository = $this->objectManager->get(OrderRepositoryInterface::class);
        $orders = $orderRepository->getList($searchCriteria)->getItems();

        return array_pop($orders);
    }
}
