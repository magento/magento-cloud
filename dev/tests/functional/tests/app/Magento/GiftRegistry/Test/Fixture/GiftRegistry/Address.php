<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Fixture\GiftRegistry;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Prepare Address for gift registry.
 */
class Address extends DataSource
{
    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data [optional]
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data = [])
    {
        $this->params = $params;
        if (isset($data['dataset'])) {
            $address = $fixtureFactory->createByCode('address', ['dataset' => $data['dataset']]);
            $this->data = $address->getData();
        } else {
            $this->data = $data;
        }
    }
}
