<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Fixture\Banner;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\SalesRule\Test\Fixture\SalesRule;
use Magento\CatalogRule\Test\Fixture\CatalogRule;

/**
 * Prepare sales rules.
 */
class SalesRules extends DataSource
{
    /**
     * Return sales rules.
     *
     * @var CatalogRule
     */
    protected $salesRules = [];

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
            $datasets = explode(',', $data['dataset']);
            foreach ($datasets as $dataset) {
                /** @var SalesRule $salesRules */
                $salesRules = $fixtureFactory->createByCode('salesRule', ['dataset' => $dataset]);
                if (!$salesRules->getRuleId()) {
                    $salesRules->persist();
                }

                $this->data[] = $salesRules->getRuleId();
                $this->salesRules[] = $salesRules;
            }
        } else {
            $this->data[] = $data;
        }
    }

    /**
     * Return sales rules fixture.
     *
     * @return CatalogRule
     */
    public function getSalesRules()
    {
        return $this->salesRules;
    }
}
