<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\TestCase;

use Magento\Rma\Test\Fixture\Rma;
use Magento\Rma\Test\Page\Adminhtml\RmaIndex;
use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\ObjectManager;
use Magento\Mtf\TestCase\Injectable;

/**
 * Base class for rma entity test.
 */
class AbstractRmaEntityTest extends Injectable
{
    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Rma index page on backend.
     *
     * @var RmaIndex
     */
    protected $rmaIndex;

    /**
     * Prepare data.
     *
     * @param FixtureFactory $fixtureFactory
     * @param RmaIndex $rmaIndex
     * @return void
     */
    public function __prepare(FixtureFactory $fixtureFactory, RmaIndex $rmaIndex)
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->rmaIndex = $rmaIndex;
    }

    /**
     * Get rma id.
     *
     * @param Rma $rma
     * @return string
     */
    protected function getRmaId(Rma $rma)
    {
        $orderId = $rma->getOrderId();
        $filter = [
            'order_id' => $orderId,
        ];

        $this->rmaIndex->open();
        $this->rmaIndex->getRmaGrid()->sortGridByField('increment_id', 'desc');
        $this->rmaIndex->getRmaGrid()->search($filter);

        $rowsData = $this->rmaIndex->getRmaGrid()->getRowsData(['rma-number']);
        return $rowsData[0]['rma-number'];
    }

    /**
     * Create rma entity.
     *
     * @param Rma $rma
     * @param array $data
     * @return Rma
     */
    protected function createRma(Rma $rma, array $data)
    {
        $rmaData = $rma->getData();
        $rmaData['order_id'] = ['data' => $this->getOrderData($rma)];
        $rmaData = array_replace_recursive($rmaData, $data);

        return $this->fixtureFactory->createByCode('rma', ['data' => $rmaData]);
    }

    /**
     * Return order data of rma entity.
     *
     * @param Rma $rma
     * @return array
     */
    protected function getOrderData(Rma $rma)
    {
        /** @var OrderInjectable $order */
        $order = $rma->getDataFieldConfig('order_id')['source']->getOrder();
        $store = $order->getDataFieldConfig('store_id')['source']->getStore();

        $data = $order->getData();
        $data['store_id'] = ['data' => $store->getData()];
        $data['entity_id'] = ['value' => $order->getEntityId()];
        if ($order->getData('customer_id')) {
            $data['customer_id'] = ['customer' => $order->getDataFieldConfig('customer_id')['source']->getCustomer()];
        } else {
            $data['billing_address_id'] = [
                'value' => $order->getDataFieldConfig('billing_address_id')['source']->getData()
            ];
        }
        return $data;
    }
}
