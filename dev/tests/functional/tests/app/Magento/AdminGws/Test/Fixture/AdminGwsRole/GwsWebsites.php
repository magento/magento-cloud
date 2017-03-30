<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdminGws\Test\Fixture\AdminGwsRole;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Store\Test\Fixture\Website;

/**
 * Source for Website list of AdminGwsRole.
 */
class GwsWebsites extends DataSource
{
    /**
     * Website list.
     *
     * @var Website[]
     */
    protected $websites = [];

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
                $this->websites[] = $fixtureFactory->createByCode('website', ['dataset' => $dataset]);
            }
        }
        foreach ($this->websites as $website) {
            $this->data[] = $website->getName();
        }
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
