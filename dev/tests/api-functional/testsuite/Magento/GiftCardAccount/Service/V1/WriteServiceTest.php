<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Service\V1;

use Magento\TestFramework\TestCase\WebapiAbstract;

class WriteServiceTest extends WebapiAbstract
{
    const SERVICE_VERSION = 'V1';
    const SERVICE_NAME = 'giftCardAccountGiftCardAccountManagementV1';
    const RESOURCE_PATH = '/V1/carts/';

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
    }

    /**
     * @magentoApiDataFixture Magento/GiftCardAccount/_files/quote_with_giftcard_saved.php
     */
    public function testDelete()
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_1', 'reserved_order_id');
        $quoteId = $quote->getId();
        $requestData = [
            'cartId' => $quoteId,
            'giftCardCode' => 'giftcardaccount_fixture',
        ];
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . $quoteId . '/giftCards/giftcardaccount_fixture',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_DELETE,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'DeleteByQuoteId',
            ],
        ];
        $result = $this->_webApiCall($serviceInfo, $requestData);
        $this->assertTrue($result);
        $quote->load('test_order_1', 'reserved_order_id');
        $this->assertEquals('[]', $quote->getGiftCards());
    }

    /**
     * @magentoApiDataFixture Magento/Checkout/_files/quote_with_address_saved.php
     * @magentoApiDataFixture Magento/GiftCardAccount/_files/giftcardaccount.php
     */
    public function testSetCouponSuccess()
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_1', 'reserved_order_id');
        $quoteId = $quote->getId();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . $quoteId . '/giftCards',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_PUT,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'SaveByQuoteId',
            ],
        ];
        $requestData = [
            'cartId' => $quoteId,
            'giftCardAccountData' => [
                'giftCards' => ['giftcardaccount_fixture'],
                'giftCardsAmount' => $quote->getGiftCardsAmount(),
                'baseGiftCardsAmount' => $quote->getBaseGiftCardsAmount(),
                'giftCardsAmountUsed' => $quote->getGiftCardsAmountUsed(),
                'baseGiftCardsAmountUsed' => $quote->getBaseGiftCardsAmountUsed(),
            ],
        ];

        $result = $this->_webApiCall($serviceInfo, $requestData);
        $this->assertTrue($result);
        $quote->load('test_order_1', 'reserved_order_id');
        $this->assertContains('giftcardaccount_fixture', $quote->getGiftCards());
    }
}
