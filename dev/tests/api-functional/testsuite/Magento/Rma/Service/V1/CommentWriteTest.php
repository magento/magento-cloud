<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Service\V1;

use Magento\Rma\Model\Rma\Status\History;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class CommentWriteTest
 */
class CommentWriteTest extends WebapiAbstract
{
    /**#@+
     * Constants defined for Web Api call
     */
    const SERVICE_VERSION = 'V1';
    const SERVICE_NAME = 'rmaCommentManagementV1';
    /**#@-*/

    /**
     * @magentoApiDataFixture Magento/Rma/_files/rma.php
     */
    public function testAddComment()
    {
        $rma = $this->getRmaFixture();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/returns/' . $rma->getId() . '/comments',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'addComment',
            ],
        ];

        //$requestData = ['id'=> $order->getId(), 'statusHistory' => $commentData];
        $requestData = [
            'id' => $rma->getId(),
            'data' => [
                History::ENTITY_ID => null,
                History::COMMENT => 'Comment',
                'customer_notified' => false,
                'visible_on_front' => true,
                History::CREATED_AT => null,
                'admin' => null,
                History::STATUS => null,
                'rma_entity_id' => $rma->getId(),
            ],
        ];

        $this->assertTrue($this->_webApiCall($serviceInfo, $requestData));
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
