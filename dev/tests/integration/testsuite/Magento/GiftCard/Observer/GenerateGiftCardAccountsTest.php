<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCard\Observer;

use Magento\Authorizenet\Model\Directpost;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class GenerateGiftCardAccountsTest
 */
class GenerateGiftCardAccountsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * Tests the controller for declines
     *
     * @magentoDataFixture Magento/GiftCard/_files/giftcard_on_ordered_setting.php
     * @magentoDataFixture Magento/GiftCardAccount/_files/codes_pool.php
     * @magentoDataFixture Magento/GiftCard/_files/gift_card.php
     * @magentoDataFixture Magento/GiftCard/_files/order_with_gift_card.php
     */
    public function testGiftcardGeneratorOnOrderAfterSaveSetting()
    {
        $order = $this->getOrder();
        /** @var \Magento\Framework\App\Config\ScopeConfigInterface $config */
        $config = $this->objectManager->get(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $giftcardSetting = $config->getValue(
            \Magento\GiftCard\Model\Giftcard::XML_PATH_ORDER_ITEM_STATUS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $order->getStore()
        );
        $this->assertEquals(\Magento\Sales\Model\Order\Item::STATUS_PENDING, $giftcardSetting);
        /** @var \Magento\Sales\Model\Order\Item $orderItem */
        $orderItem = current($order->getItems());
        $productOptions = $orderItem->getProductOptions();
        $this->assertArrayHasKey('email_sent', $productOptions);
        $this->assertArrayHasKey('giftcard_created_codes', $productOptions);
        $this->assertEquals('1', $productOptions['email_sent']);
        $this->assertEquals(['fixture_code_2'], $productOptions['giftcard_created_codes']);
    }

    /**
     * Tests the controller for declines
     *
     * @magentoDataFixture Magento/GiftCardAccount/_files/codes_pool.php
     * @magentoDataFixture Magento/GiftCard/_files/giftcard_on_invoiced_setting.php
     * @magentoDataFixture Magento/GiftCard/_files/gift_card.php
     * @magentoDataFixture Magento/GiftCard/_files/order_with_gift_card.php
     */
    public function testGiftcardGeneratorOnInvoiceAfterSaveSettingNoGenerate()
    {
        $order = $this->getOrder();
        /** @var \Magento\Framework\App\Config\ScopeConfigInterface $config */
        $config = $this->objectManager->get(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $giftcardSetting = $config->getValue(
            \Magento\GiftCard\Model\Giftcard::XML_PATH_ORDER_ITEM_STATUS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $order->getStore()
        );
        $this->assertEquals(\Magento\Sales\Model\Order\Item::STATUS_INVOICED, $giftcardSetting);
        /** @var \Magento\Sales\Model\Order\Item $orderItem */
        $orderItem = current($order->getItems());
        $productOptions = $orderItem->getProductOptions();
        $this->assertArrayNotHasKey('email_sent', $productOptions);
        $this->assertArrayNotHasKey('giftcard_created_codes', $productOptions);
    }

    /**
     * Tests the controller for declines
     *
     * @magentoDataFixture Magento/GiftCardAccount/_files/codes_pool.php
     * @magentoDataFixture Magento/GiftCard/_files/gift_card.php
     * @magentoDataFixture Magento/GiftCard/_files/invoice_with_gift_card.php
     */
    public function testGiftcardGeneratorOnInvoiceAfterSaveSettingGenerate()
    {
        $order = $this->getOrder();
        /** @var \Magento\Framework\App\Config\ScopeConfigInterface $config */
        $config = $this->objectManager->get(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $giftcardSetting = $config->getValue(
            \Magento\GiftCard\Model\Giftcard::XML_PATH_ORDER_ITEM_STATUS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $order->getStore()
        );
        $this->assertEquals(\Magento\Sales\Model\Order\Item::STATUS_INVOICED, $giftcardSetting);
        /** @var \Magento\Sales\Model\Order\Item $orderItem */
        $orderItem = current($order->getItems());
        $productOptions = $orderItem->getProductOptions();
        $this->assertArrayHasKey('email_sent', $productOptions, 'a');
        $this->assertArrayHasKey('giftcard_created_codes', $productOptions, 'b');
        $this->assertEquals('1', $productOptions['email_sent'], 'c');
        $this->assertEquals(1, count($productOptions['giftcard_created_codes']), 'd');
    }

    /**
     * Get stored order
     * @return \Magento\Sales\Model\Order
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
