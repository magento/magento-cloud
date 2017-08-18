<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Model;

class RmaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoDataFixture Magento/Rma/_files/order.php
     */
    public function testSaveRma()
    {
        $order = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Sales\Model\Order::class);
        $order->loadByIncrementId('100000001');
        $rma = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Rma\Model\Rma::class);
        $rmaItems = [];

        foreach ($order->getItemsCollection() as $item) {
            $rmaItems[] = [
                'order_item_id' => $item->getId(),
                'qty_requested' => '1',
                'resolution' => '3',
                'condition' => '6',
                'reason' => '10',
            ];
        }
        $data = ['customer_custom_email' => '', 'items' => $rmaItems, 'rma_comment' => 'comment'];
        $rmaData = [
            'status' => \Magento\Rma\Model\Rma\Source\Status::STATE_PENDING,
            'date_requested' => \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
                \Magento\Framework\Stdlib\DateTime\DateTime::class
            )->gmtDate(),
            'order_id' => $order->getId(),
            'order_increment_id' => $order->getIncrementId(),
            'store_id' => $order->getStoreId(),
            'customer_id' => $order->getCustomerId(),
            'order_date' => $order->getCreatedAt(),
            'customer_name' => $order->getCustomerName(),
            'customer_custom_email' => 'example@domain.com',
        ];

        $rma->setData($rmaData)->saveRma($data);
        $rmaId = $rma->getId();

        unset($rma);
        $rma = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Rma\Model\Rma::class);
        $rma->load($rmaId);
        $this->assertEquals($rma->getId(), $rmaId);
        $this->assertEquals($rma->getOrderId(), $order->getId());
        $this->assertEquals($rma->getCustomerCustomEmail(), $rmaData['customer_custom_email']);
        $this->assertEquals($rma->getOrderIncrementId(), $order->getIncrementId());
        $this->assertEquals($rma->getStoreId(), $order->getStoreId());
        $this->assertEquals($rma->getStatus(), \Magento\Rma\Model\Rma\Source\Status::STATE_PENDING);
    }
}
