<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Fixture\Banner;

use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Fixture\DataSource;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment as CustomerSegmentFixture;

/**
 * Prepare catalog rules.
 */
class CustomerSegmentIds extends DataSource
{
    /**
     * Return customer segment.
     *
     * @var CustomerSegmentFixture
     */
    protected $customerSegment = [];

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data = [])
    {
        $this->params = $params;
        if ($data['dataset'] && $data['dataset'] != "-") {
            $dataset = explode(',', $data['dataset']);
            foreach ($dataset as $customerSegment) {
                /** @var CustomerSegmentFixture $segment */
                $segment = $fixtureFactory->createByCode('customerSegment', ['dataset' => $customerSegment]);
                if (!$segment->getSegmentId()) {
                    $segment->persist();
                }
                $this->customerSegment[] = $segment;
                $this->data[] = $segment->getSegmentId();
            }
        } else {
            $this->data[] = null;
        }
    }

    /**
     * Return customer segment fixture.
     *
     * @return CustomerSegmentFixture
     */
    public function getCustomerSegments()
    {
        return $this->customerSegment;
    }
}
