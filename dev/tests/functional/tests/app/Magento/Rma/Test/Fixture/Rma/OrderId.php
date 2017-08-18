<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Fixture\Rma;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Sales\Test\Fixture\OrderInjectable;

/**
 * Source rma order id.
 */
class OrderId extends DataSource
{
    /**
     * Order source.
     *
     * @var OrderInjectable
     */
    protected $order = null;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data [optional]
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data = [])
    {
        $this->params = $params;

        $dataset = isset($data['dataset']) ? $data['dataset'] : '';
        $data = isset($data['data']) ? $data['data'] : [];
        if ($data) {
            $this->order = $fixtureFactory->createByCode(
                'orderInjectable',
                [
                    'dataset' => $dataset,
                    'data' => $data
                ]
            );
            if (!$this->order->hasData('id')) {
                $this->order->persist();
            }
            $this->data = $this->order->getData('id');
        }
    }

    /**
     * Return order source.
     *
     * @return OrderInjectable|null
     */
    public function getOrder()
    {
        return $this->order;
    }
}
