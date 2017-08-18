<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardAccount\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * API test that checks retrieving of Invoice(s) with gift card account data.
 */
class RetrieveInvoiceTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/invoices';
    const SERVICE_READ_NAME = 'salesInvoiceRepositoryV1';
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
     * @magentoApiDataFixture Magento/GiftCardAccount/_files/invoice_with_gift_card_account.php
     */
    public function testInvoiceGet()
    {
        /** @var \Magento\Sales\Model\Order\Invoice $invoice */
        $invoiceCollection = $this->objectManager->get(
            \Magento\Sales\Model\ResourceModel\Order\Invoice\Collection::class
        );

        $invoice = $invoiceCollection->getFirstItem();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $invoice->getId(),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
            ],
            'soap' => [
                'service' => self::SERVICE_READ_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_READ_NAME . 'get'
            ]
        ];

        $this->assertInvoiceData(
            $this->_webApiCall($serviceInfo, ['id' => $invoice->getId()])
        );
    }

    /**
     * @magentoApiDataFixture Magento/GiftCardAccount/_files/invoice_with_gift_card_account.php
     */
    public function testInvoiceGetList()
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
                    ->setValue((string) \Magento\Sales\Model\Order\Invoice::STATE_PAID)
                    ->setConditionType('eq')
                    ->create()
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

        $this->assertInvoiceData($result['items'][0]);
    }

    /**
     * @param array $invoiceData
     */
    private function assertInvoiceData(array $invoiceData)
    {
        $this->assertArrayHasKey('extension_attributes', $invoiceData);

        $this->assertArrayHasKey('base_gift_cards_amount', $invoiceData['extension_attributes']);
        $this->assertArrayHasKey('gift_cards_amount', $invoiceData['extension_attributes']);

        $this->assertEquals(10, $invoiceData['extension_attributes']['base_gift_cards_amount']);
        $this->assertEquals(10, $invoiceData['extension_attributes']['gift_cards_amount']);
    }
}
