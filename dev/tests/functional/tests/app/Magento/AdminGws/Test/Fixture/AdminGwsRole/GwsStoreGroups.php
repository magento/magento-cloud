<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdminGws\Test\Fixture\AdminGwsRole;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Store\Test\Fixture\StoreGroup;

/**
 * Source for StoreGroup list of AdminGwsRole.
 */
class GwsStoreGroups extends DataSource
{
    /**
     * StoreGroup list.
     *
     * @var StoreGroup[]
     */
    protected $storesGroups = [];

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data = [])
    {
        $this->params = $params;

        if (isset($data['dataset'])) {
            $datasets = array_map('trim', explode(',', $data['dataset']));

            foreach ($datasets as $dataset) {
                $this->storesGroups[] = $fixtureFactory->createByCode('storeGroup', ['dataset' => $dataset]);
            }
        }
        foreach ($this->storesGroups as $storeGroup) {
            $this->data[] = $storeGroup->getName();
        }
    }

    /**
     * Return prepared StoreGroup list.
     *
     * @return StoreGroup[]
     */
    public function getStoreGroups()
    {
        return $this->storesGroups;
    }
}
