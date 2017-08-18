<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Test\Fixture\Reminder;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\SalesRule\Test\Fixture\SalesRule;

/**
 * Source sales rule.
 */
class SalesruleId extends DataSource
{
    /**
     * Fixture sales rule.
     *
     * @var SalesRule
     */
    protected $salesRule;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data
     */
    public function __construct(
        FixtureFactory $fixtureFactory,
        array $params,
        array $data = []
    ) {
        $this->params = $params;
        $this->salesRule = $fixtureFactory->createByCode('salesRule', $data);

        if (!$this->salesRule->hasData('rule_id')) {
            $this->salesRule->persist();
        }
        $this->data = $this->salesRule->getName();
    }

    /**
     * Get sales rule.
     *
     * @return SalesRule
     */
    public function getSalesRule()
    {
        return $this->salesRule;
    }
}
