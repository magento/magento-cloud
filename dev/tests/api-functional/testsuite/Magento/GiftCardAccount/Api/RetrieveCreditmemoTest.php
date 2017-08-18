<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardAccount\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * API test that checks retrieving of Creditmemo(s) with gift card account data.
 */
class RetrieveCreditmemoTest extends WebapiAbstract
{
    const RESOURCE_PATH_GET = '/V1/creditmemo';
    const RESOURCE_PATH_GET_LIST = '/V1/creditmemos';
    const SERVICE_READ_NAME = 'salesCreditmemoRepositoryV1';
    const SERVICE_VERSION = 'V1';

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
    }

    /**
     * @magentoApiDataFixture Magento/GiftCardAccount/_files/creditmemo_with_gift_card_account.php
     */
    public function testCreditmemoGet()
    {
        /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
        $creditmemoCollection = $this->objectManager->get(
            \Magento\Sales\Model\ResourceModel\Order\Creditmemo\Collection::class
        );

        $creditmemo = $creditmemoCollection->getFirstItem();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH_GET . '/' . $creditmemo->getId(),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
            ],
            'soap' => [
                'service' => self::SERVICE_READ_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_READ_NAME . 'get'
            ]
        ];

        $this->assertCreditmemoData(
            $this->_webApiCall($serviceInfo, ['id' => $creditmemo->getId()])
        );
    }

    /**
     * @magentoApiDataFixture Magento/GiftCardAccount/_files/creditmemo_with_gift_card_account.php
     */
    public function testCreditmemoGetList()
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
                    ->setField('state')
                    ->setValue((string)\Magento\Sales\Model\Order\Creditmemo::STATE_OPEN)
                    ->setConditionType('eq')
                    ->create(),
            ]
        );

        $requestData = [
            'searchCriteria' => $searchCriteriaBuilder->create()->__toArray()
        ];

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH_GET_LIST . '?' . http_build_query($requestData),
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

        $this->assertCreditmemoData($result['items'][0]);
    }

    /**
     * @param array $creditmemoData
     */
    private function assertCreditmemoData(array $creditmemoData)
    {
        $this->assertArrayHasKey('extension_attributes', $creditmemoData);

        $this->assertArrayHasKey('base_gift_cards_amount', $creditmemoData['extension_attributes']);
        $this->assertArrayHasKey('gift_cards_amount', $creditmemoData['extension_attributes']);

        $this->assertEquals(10, $creditmemoData['extension_attributes']['base_gift_cards_amount']);
        $this->assertEquals(10, $creditmemoData['extension_attributes']['gift_cards_amount']);
    }
}
