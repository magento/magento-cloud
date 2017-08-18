<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Service\V1;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Webapi\Rest\Request;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;
use Magento\Rma\Model\Rma;
use Magento\Framework\ObjectManagerInterface;

class RmaListTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/returns/';

    const SERVICE_NAME_SEARCH = 'rmaRmaManagementV1';

    const SERVICE_VERSION = 'V1';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * @magentoApiDataFixture Magento/Rma/_files/rma_list.php
     */
    public function testGetList()
    {
        /** @var $searchCriteriaBuilder SearchCriteriaBuilder */
        $searchCriteriaBuilder = $this->objectManager->create(
            SearchCriteriaBuilder::class
        );

        /** @var $filterBuilder  FilterBuilder */
        $filterBuilder = $this->objectManager->create(
            FilterBuilder::class
        );
        $filter1 = $filterBuilder
            ->setField(Rma::INCREMENT_ID)
            ->setValue('100000002')
            ->setConditionType('eq')
            ->create();
        $filter2 = $filterBuilder
            ->setField(Rma::INCREMENT_ID)
            ->setValue('100000003')
            ->setConditionType('eq')
            ->create();
        $filter3 = $filterBuilder
            ->setField(Rma::INCREMENT_ID)
            ->setValue('100000004')
            ->setConditionType('eq')
            ->create();
        $searchCriteriaBuilder->addFilters([$filter1, $filter2, $filter3]);
        $searchData = $searchCriteriaBuilder->create()->__toArray();

        $requestData = ['searchCriteria' => $searchData];

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($requestData),
                'httpMethod' => Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME_SEARCH,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME_SEARCH . 'search',
            ],
        ];

        $result = $this->_webApiCall($serviceInfo, $requestData);

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(3, $result['items']);
        $this->assertEquals('100000002', $result['items'][0]['increment_id']);
        $this->assertEquals('100000003', $result['items'][1]['increment_id']);
        $this->assertEquals('100000004', $result['items'][2]['increment_id']);
    }
}
