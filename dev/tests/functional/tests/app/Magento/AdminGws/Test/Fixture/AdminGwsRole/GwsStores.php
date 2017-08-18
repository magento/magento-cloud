<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdminGws\Test\Fixture\AdminGwsRole;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Store\Test\Fixture\Store;
use Magento\Store\Test\Fixture\StoreGroup;
use Magento\Store\Test\Fixture\Website;

/**
 * Source for Store list of AdminGwsRole.
 */
class GwsStores extends DataSource
{
    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Invoice data.
     *
     * @var array
     */
    protected $data;

    /**
     * Store list.
     *
     * @var Store[]
     */
    protected $stores = [];

    /**
     * StoreGroup list.
     *
     * @var StoreGroup[]
     */
    protected $storesGroups = [];

    /**
     * Website list.
     *
     * @var Website[]
     */
    protected $websites = [];

    /**
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params)
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->params = $params;
        $this->getData();
    }

    /**
     * Prepare data.
     *
     * @param string $key [optional]
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getData($key = null)
    {
        if (isset($this->data['dataset'])) {
            $datasets = array_map('trim', explode(',', $this->data['dataset']));

            foreach ($datasets as $dataset) {
                $this->stores[] = $this->fixtureFactory->createByCode('store', ['dataset' => $dataset]);
            }
        }
        foreach ($this->stores as $store) {
            if (!$store->hasData('store_id')) {
                $store->persist();
            }
            $this->storesGroups[] = $store->getDataFieldConfig('group_id')['source']
                ->getStoreGroup();
            $this->websites[] = $store->getDataFieldConfig('group_id')['source']
                ->getStoreGroup()->getDataFieldConfig('website_id')['source']->getWebsite();
            $this->data[] = $store->getName();
        }
    }

    /**
     * Return prepared Store list.
     *
     * @return StoreGroup[]
     */
    public function getStores()
    {
        return $this->stores;
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

    /**
     * Return prepared Website list.
     *
     * @return Website[]
     */
    public function getWebsites()
    {
        return $this->websites;
    }
}
