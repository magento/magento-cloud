<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Fixture\GiftWrapping;

use Magento\Store\Test\Fixture\Website;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Fixture\DataSource;

/**
 * Prepare Website id for Gift Wrapping creation.
 *
 * Data keys:
 *  - dataset
 */
class WebsiteIds extends DataSource
{
    /**
     * Array with website fixtures.
     *
     * @var Website
     */
    protected $websites;

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
            $datasets = is_array($data['dataset']) ? $data['dataset'] : [$data['dataset']];
            foreach ($datasets as $dataset) {
                $website = $fixtureFactory->createByCode('website', ['dataset' => $dataset]);
                /** @var Website $website */
                if (!$website->getWebsiteId()) {
                    $website->persist();
                }
                $this->websites[] = $website;
                $this->data[] = $website->getName();
            }
        }
    }

    /**
     * Return Website fixtures.
     *
     * @return Website
     */
    public function getWebsites()
    {
        return $this->websites;
    }
}
