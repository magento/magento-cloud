<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Service\V1;

use Magento\Rma\Model\Rma;
use Magento\TestFramework\TestCase\WebapiAbstract;

class RmaReadTest extends WebapiAbstract
{
    /**#@+
     * Constants defined for Web Api call
     */
    const SERVICE_VERSION = 'V1';
    const SERVICE_NAME_SEARCH = 'rmaRmaManagementV1';
    const SERVICE_NAME_GET = 'rmaRmaRepositoryV1';
    /**#@-*/

    /**
     * @magentoApiDataFixture Magento/Rma/_files/rma.php
     */
    public function testGet()
    {
        $rma = $this->getRmaFixture();
        $rmaId = $rma->getId();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/returns/' . $rmaId,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME_GET,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME_GET . 'get',
            ],
        ];

        $result = $this->_webApiCall($serviceInfo, ['id' => $rmaId]);
        $this->assertEquals($rmaId, $result[Rma::ENTITY_ID]);
    }

    public function testSearch()
    {
        $rma = $this->getRmaFixture();
        $rmaId = $rma->getId();

        $request = [
            'searchCriteria' => [
                'filterGroups' => [
                    [
                        'filters' => [
                            [
                                'field' => Rma::ENTITY_ID,
                                'value' => $rmaId,
                                'conditionType' => 'eq',
                            ]
                        ],
                    ],
                ],
            ],
        ];
        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/returns' . '?' . http_build_query($request),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME_SEARCH,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME_SEARCH . 'search',
            ],
        ];

        $result = $this->_webApiCall($serviceInfo, $request);
        $this->assertNotEmpty($rmaId, $result['items']);
    }

    /**
     * Return last created Rma fixture
     *
     * @return \Magento\Rma\Model\Rma
     */
    private function getRmaFixture()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $collection = $objectManager->create(\Magento\Rma\Model\ResourceModel\Rma\Collection::class);
        $collection->setOrder('entity_id')
            ->setPageSize(1)
            ->load();
        return $collection->fetchItem();
    }
}
