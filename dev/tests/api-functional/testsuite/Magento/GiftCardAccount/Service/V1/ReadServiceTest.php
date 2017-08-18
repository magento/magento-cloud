<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Service\V1;

use Magento\GiftCardAccount\Model\Giftcardaccount as GiftCardAccount;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class ReadServiceTest
 */
class ReadServiceTest extends WebapiAbstract
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
    public function testGetList()
    {
        /** @var \Magento\Quote\Model\Quote  $quote */
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test_order_1', 'reserved_order_id');
        $quoteId = $quote->getId();

        $data = [
            GiftCardAccount::GIFT_CARDS => ['giftcardaccount_fixture'],
            GiftCardAccount::GIFT_CARDS_AMOUNT => $quote->getGiftCardsAmount(),
            GiftCardAccount::BASE_GIFT_CARDS_AMOUNT => $quote->getBaseGiftCardsAmount(),
            GiftCardAccount::GIFT_CARDS_AMOUNT_USED => $quote->getGiftCardsAmountUsed(),
            GiftCardAccount::BASE_GIFT_CARDS_AMOUNT_USED => $quote->getBaseGiftCardsAmountUsed(),
        ];
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . $quoteId . '/giftCards',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'GetListByQuoteId',
            ],
        ];

        $requestData = ["quoteId" => $quoteId];
        $result = $this->_webApiCall($serviceInfo, $requestData);
        $this->assertEquals($data, $result);
    }
}
