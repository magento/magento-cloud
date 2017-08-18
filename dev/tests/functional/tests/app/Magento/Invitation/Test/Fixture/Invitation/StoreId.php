<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Fixture\Invitation;

use Magento\Mtf\Fixture\DataSource;
use Magento\Store\Test\Fixture\Store;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Prepare StoreId.
 */
class StoreId extends DataSource
{
    /**
     * Store fixture.
     *
     * @var Store
     */
    public $store;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $data
     * @param array $params [optional]
     */
    public function __construct(FixtureFactory $fixtureFactory, array $data, array $params = [])
    {
        $this->params = $params;
        if (isset($data['dataset'])) {
            $store = $fixtureFactory->createByCode('store', ['dataset' => $data['dataset']]);
            if (!$store->getStoreId()) {
                $store->persist();
            }
            $this->store = $store;
            $this->data = $store->getName();
        }
    }

    /**
     * Return Store fixture.
     *
     * @return Store
     */
    public function getStore()
    {
        return $this->store;
    }
}
