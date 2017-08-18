<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
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
                $website = $fixtureFactory->createByCode('website', ['dataset' => $dataset]);
                if (!$website->hasData('website_id')) {
                    $website->persist();
                }
                $this->websites[] = $website;
            }
        }
        foreach ($this->websites as $website) {
            if (!$website->hasData('website_id')) {
                $website->persist();
            }
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
