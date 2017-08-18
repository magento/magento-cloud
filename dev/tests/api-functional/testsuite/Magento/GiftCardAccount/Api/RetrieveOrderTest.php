<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardAccount\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * API test that checks retrieving of Order(s) with gift card account data.
 */
class RetrieveOrderTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/orders';
    const SERVICE_READ_NAME = 'salesOrderRepositoryV1';
    const SERVICE_VERSION = 'V1';
    const ORDER_INCREMENT_ID = '100000001';

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
    }

    /**
     * @magentoApiDataFixture Magento/GiftCardAccount/_files/order_with_gift_card_account.php
     */
    public function testOrderGet()
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->objectManager->create(\Magento\Sales\Model\Order::class);

        $order->loadByIncrementId(self::ORDER_INCREMENT_ID);

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $order->getId(),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
            ],
            'soap' => [
                'service' => self::SERVICE_READ_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_READ_NAME . 'get'
            ]
        ];

        $this->assertOrderData(
            $this->_webApiCall($serviceInfo, ['id' => $order->getId()])
        );
    }

    /**
     * @magentoApiDataFixture Magento/GiftCardAccount/_files/order_with_gift_card_account.php
     */
    public function testOrderGetList()
    {
        /** @var \Magento\Framework\Api\FilterBuilder $filterBuilder */
        $filterBuilder = $this->objectManager->create(
            \Magento\Framework\Api\FilterBuilder::class
        );

        /** @var \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder */
        $searchCriteriaBuilder = $this->objectManager->create(
            \Magento\Framework\Api\SearchCriteriaBuilder::class
        );

        $searchCriteriaBuilder->addFilters(
            [
                $filterBuilder
                    ->setField('status')
                    ->setValue('processing')
                    ->setConditionType('eq')
                    ->create(),
            ]
        );

        $requestData = [
            'searchCriteria' => $searchCriteriaBuilder->create()->__toArray()
        ];

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($requestData),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
            ],
            'soap' => [
                'service' => self::SERVICE_READ_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_READ_NAME . 'getList'
            ]
        ];

        $result = $this->_webApiCall($serviceInfo, $requestData);

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(1, $result['items']);

        $this->assertOrderData($result['items'][0]);
    }

    /**
     * @param array $orderData
     */
    private function assertOrderData(array $orderData)
    {
        $this->assertArrayHasKey('extension_attributes', $orderData);

        $this->assertArrayHasKey('gift_cards', $orderData['extension_attributes']);
        $this->assertArrayHasKey('base_gift_cards_amount', $orderData['extension_attributes']);
        $this->assertArrayHasKey('gift_cards_amount', $orderData['extension_attributes']);
        $this->assertArrayHasKey('base_gift_cards_invoiced', $orderData['extension_attributes']);
        $this->assertArrayHasKey('gift_cards_invoiced', $orderData['extension_attributes']);
        $this->assertArrayHasKey('base_gift_cards_refunded', $orderData['extension_attributes']);
        $this->assertArrayHasKey('gift_cards_refunded', $orderData['extension_attributes']);

        $giftCards = [
            [
                "id" => 1,
                "code" => 'TESTCODE1',
                "amount" => 10,
                "base_amount" => 10,
            ],
            [
                "id" => 2,
                "code" => 'TESTCODE2',
                "amount" => 15,
                "base_amount" => 15,
            ],
        ];

        $this->assertEquals($giftCards, $orderData['extension_attributes']['gift_cards']);
        $this->assertEquals(20, $orderData['extension_attributes']['base_gift_cards_amount']);
        $this->assertEquals(20, $orderData['extension_attributes']['gift_cards_amount']);
        $this->assertEquals(10, $orderData['extension_attributes']['base_gift_cards_invoiced']);
        $this->assertEquals(10, $orderData['extension_attributes']['gift_cards_invoiced']);
        $this->assertEquals(5, $orderData['extension_attributes']['base_gift_cards_refunded']);
        $this->assertEquals(5, $orderData['extension_attributes']['gift_cards_refunded']);
    }
}
