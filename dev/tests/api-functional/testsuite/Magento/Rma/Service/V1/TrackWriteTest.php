<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Service\V1;

use Magento\Rma\Model\Shipping;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class TrackWriteTest
 */
class TrackWriteTest extends WebapiAbstract
{
    /**#@+
     * Constants defined for Web Api call
     */
    const SERVICE_VERSION = 'V1';
    const SERVICE_NAME = 'rmaTrackManagementV1';
    /**#@-*/

    /**
     * @magentoApiDataFixture Magento/Rma/_files/rma.php
     */
    public function testAddTrack()
    {
        $rma = $this->getRmaFixture();
        $rmaId = $rma->getId();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/returns/' . $rma->getId() . '/tracking-numbers',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'addTrack',
            ],
        ];

        $requestData = [
            'id' => $rmaId,
            'track' => [
                Shipping::ENTITY_ID => null,
                Shipping::TRACK_NUMBER => 'Track Number',
                Shipping::RMA_ENTITY_ID => $rmaId,
                Shipping::CARRIER_TITLE => 'Carrier title',
                Shipping::CARRIER_CODE => 'custom',
            ],
        ];

        $this->assertTrue($this->_webApiCall($serviceInfo, $requestData));
    }

    public function testRemoveTrackById()
    {
        $rma = $this->getRmaFixture();
        $track = $rma->getTrackingNumbers()->load()->fetchItem();
        $rmaId = $rma->getId();
        $trackId = $track->getId();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/returns/' . $rma->getId() . '/tracking-numbers/' . $track->getId(),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_DELETE,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'removeTrackById',
            ],
        ];

        $this->assertTrue($this->_webApiCall($serviceInfo, ['id' => $rmaId, 'trackId' => $trackId]));
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
