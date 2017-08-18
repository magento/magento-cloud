<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Fixture\Update;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Base class for creating products for update campaign.
 */
class Product extends DataSource
{
    /**
     * Url for saving data
     *
     * @var string
     */
    private $saveUrl = 'catalogstaging/product/save/';

    /**
     * Rough fixture field data.
     *
     * @var array
     */
    private $fixtureData = [];

    /**
     * Fixture Factory.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Save data URL.
     *
     * @var array
     */
    private $url = [];

    /**
     * @param FixtureFactory $fixtureFactory
     * @param array $data [optional]
     */
    public function __construct(
        FixtureFactory $fixtureFactory,
        array $data = []
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $this->fixtureData = $data;
    }

    /**
     * Return prepared data set.
     *
     * @param string $key [optional]
     * @return mixed
     * @throws \Exception
     */
    public function getData($key = null)
    {
        if (!isset($this->fixtureData['dataset'])) {
            throw new \Exception("Data must be set");
        }
        list($fixtureCode, $dataset) = explode('::', $this->fixtureData['dataset']);
        $product = $this->fixtureFactory->createByCode($fixtureCode, ['dataset' => $dataset]);
        if (!$product->getId()) {
            $product->persist();
        }
        $this->data = $product;
        $urlParams = $product->getDataConfig()['create_url_params'];
        $stores = $product->getDataFieldConfig('website_ids')['source']->getStores();
        foreach ($stores as $store) {
            $this->url[] = $this->saveUrl . 'id/' . $product->getId() . '/type/'
                . $urlParams['type'] . '/store/' . $store->getStoreId() . '/set/' . $urlParams['set'];
        }
        return parent::getData($key);
    }

    /**
     * Return save data URL.
     *
     * @return array
     */
    public function getUrl()
    {
        return $this->url;
    }
}
