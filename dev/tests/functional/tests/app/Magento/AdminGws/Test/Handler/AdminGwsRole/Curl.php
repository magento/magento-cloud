<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdminGws\Test\Handler\AdminGwsRole;

use Magento\AdminGws\Test\Fixture\AdminGwsRole;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Store\Test\Fixture\StoreGroup;
use Magento\Store\Test\Fixture\Website;
use Magento\User\Test\Handler\Role\Curl as RoleCurl;

/**
 * Create new Role with the Global, Website, and Store data scopes via curl.
 */
class Curl extends RoleCurl implements AdminGwsRoleInterface
{
    /**
     * Additional mapping values for data.
     *
     * @var array
     */
    protected $additionalMappingData = [
        'gws_is_all' => [
            'All' => 1,
            'Custom' => 0
        ]
    ];

    /**
     * Prepare fixture data before send.
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    protected function prepareData(FixtureInterface $fixture = null)
    {
        $data = parent::prepareData($fixture);
        if (isset($data['gws_store_groups'])) {
            /** @var AdminGwsRole $fixture */
            $storeGroups = $fixture->getDataFieldConfig('gws_store_groups')['source']->getStoreGroups();

            $data['gws_store_groups'] = [];
            foreach ($storeGroups as $storeGroup) {
                /** @var StoreGroup $storeGroup */
                $data['gws_store_groups'][] = $storeGroup->getGroupId();
            }
        }
        if (isset($data['gws_websites'])) {
            /** @var AdminGwsRole $fixture */
            $websites = $fixture->getDataFieldConfig('gws_websites')['source']->getWebsites();

            $data['gws_websites'] = [];
            foreach ($websites as $website) {
                /** @var Website $website */
                $data['gws_websites'][] = $website->getWebsiteId();
            }
        }
        if (isset($data['gws_stores'])) {
            /** @var AdminGwsRole $fixture */
            $storeGroups = $fixture->getDataFieldConfig('gws_stores')['source']->getStoreGroups();
            $data['gws_store_groups'] = [];
            foreach ($storeGroups as $storeGroup) {
                /** @var StoreGroup $storeGroup */
                $data['gws_store_groups'][] = $storeGroup->getGroupId();
            }
        }

        if (isset($data['gws_websites'])) {
            /** @var AdminGwsRole $fixture */
            $websites = $fixture->getDataFieldConfig('gws_websites')['source']->getWebsites();

            $data['gws_websites'] = [];
            foreach ($websites as $website) {
                /** @var Website $website */
                $data['gws_websites'][] = $website->getWebsiteId();
            }
        }

        return $data;
    }
}
