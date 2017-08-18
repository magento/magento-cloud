<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Fixture\Banner;

use Magento\CatalogRule\Test\Fixture\CatalogRule;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Fixture\DataSource;

/**
 * Prepare catalog rules.
 */
class CatalogRules extends DataSource
{
    /**
     * Return catalog rules.
     *
     * @var CatalogRule
     */
    protected $catalogRules = [];

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
                /** @var CatalogRule $catalogRule */
                $catalogRule = $fixtureFactory->createByCode('catalogRule', ['dataset' => $dataset]);
                if (!$catalogRule->getId()) {
                    $catalogRule->persist();
                }

                $this->data[] = $catalogRule->getId();
                $this->catalogRule[] = $catalogRule;
            }
        } else {
            $this->data[] = $data;
        }
    }

    /**
     * Return catalog rules fixture.
     *
     * @return CatalogRule
     */
    public function getCatalogRules()
    {
        return $this->catalogRules;
    }
}
