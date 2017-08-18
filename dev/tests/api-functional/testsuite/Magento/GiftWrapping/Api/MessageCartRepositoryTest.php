<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftWrapping\Api;

// @codingStandardsIgnoreFile

use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;

class MessageCartRepositoryTest extends WebapiAbstract
{
    const SERVICE_VERSION = 'V1';
    const SERVICE_NAME = 'giftMessageCartRepositoryV1';
    const RESOURCE_PATH = '/V1/carts/';

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * @magentoApiDataFixture Magento/GiftMessage/_files/quote_with_item_message.php
     * @magentoApiDataFixture Magento/GiftWrapping/_files/wrapping.php
     */
    public function testSave()
    {
        /** @var \Magento\GiftWrapping\Model\Wrapping $wrapping */
        $wrapping = $this->objectManager->create(\Magento\GiftWrapping\Model\Wrapping::class);
        $wrapping->load('image.png', 'image');

        $allowGiftReceipt = true;
        $allowPrintedCard = true;

        // sales/gift_options/allow_order must be set to 1 in system configuration
        // @todo remove next statement when \Magento\TestFramework\TestCase\WebapiAbstract::_updateAppConfig is fixed
        $this->markTestIncomplete('This test relies on system configuration state.');
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_message', 'reserved_order_id');

        $cartId = $quote->getId();
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . $cartId . '/gift-message',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'Save',
            ],
        ];

        $requestData = [
            'cartId' => $cartId,
            'giftMessage' => [
                'recipient' => 'John Doe',
                'sender' => 'Jane Roe',
                'message' => 'Gift Message Text New',
                'extension_attributes' => [
                    'wrapping_id' => $wrapping->getId(),
                    'wrapping_allow_gift_receipt' => $allowGiftReceipt,
                    'wrapping_add_printed_card' => $allowPrintedCard,
                ],
            ],
        ];
        $this->assertTrue($this->_webApiCall($serviceInfo, $requestData));
        $quote->load('test_order_item_with_message', 'reserved_order_id');
        $this->assertEquals($wrapping->getId(), $quote->getGwId());
        $this->assertEquals($allowGiftReceipt, $quote->getGwAllowGiftReceipt());
        $this->assertEquals($allowPrintedCard, $quote->getGwAddCard());
    }

    /**
     * @magentoApiDataFixture Magento/GiftMessage/_files/quote_with_item_message.php
     * @magentoApiDataFixture Magento/GiftWrapping/_files/wrapping.php
     */
    public function testSaveForMyCart()
    {
        $this->_markTestAsRestOnly();

        /** @var \Magento\GiftWrapping\Model\Wrapping $wrapping */
        $wrapping = $this->objectManager->create(\Magento\GiftWrapping\Model\Wrapping::class);
        $wrapping->load('image.png', 'image');

        $allowGiftReceipt = true;
        $allowPrintedCard = true;

        // get customer ID token
        /** @var \Magento\Integration\Api\CustomerTokenServiceInterface $customerTokenService */
        $customerTokenService = $this->objectManager->create(
            \Magento\Integration\Api\CustomerTokenServiceInterface::class
        );
        $token = $customerTokenService->createCustomerAccessToken('customer@example.com', 'password');

        // sales/gift_options/allow_order must be set to 1 in system configuration
        // @todo remove next statement when \Magento\TestFramework\TestCase\WebapiAbstract::_updateAppConfig is fixed
        $this->markTestIncomplete('This test relies on system configuration state.');

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . 'mine/gift-message',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
                'token' => $token,
            ],
        ];

        $requestData = [
            'giftMessage' => [
                'recipient' => 'John Doe',
                'sender' => 'Jane Roe',
                'message' => 'Gift Message Text New',
                'extension_attributes' => [
                    'wrapping_id' => $wrapping->getId(),
                    'wrapping_allow_gift_receipt' => $allowGiftReceipt,
                    'wrapping_add_printed_card' => $allowPrintedCard,
                ],
            ],
        ];
        $this->assertTrue($this->_webApiCall($serviceInfo, $requestData));

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_item_with_message', 'reserved_order_id');
        $this->assertEquals($wrapping->getId(), $quote->getGwId());
        $this->assertEquals($allowGiftReceipt, $quote->getGwAllowGiftReceipt());
        $this->assertEquals($allowPrintedCard, $quote->getGwAddCard());
    }
}
