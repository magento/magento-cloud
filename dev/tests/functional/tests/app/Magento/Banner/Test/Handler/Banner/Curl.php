<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Handler\Banner;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Curl for create banner.
 */
class Curl extends AbstractCurl implements BannerInterface
{
    /**
     * Data mapping.
     *
     * @var array
     */
    protected $mappingData = [
        'is_enabled' => [
            'Active' => 1,
            'Inactive' => 0,
        ],
        'store_contents_not_use' => [
            'Yes' => 1,
            'No' => 0,
        ],
        'type' => [
            'Any Banner Type' => 0,
            'Specified Banner Types' => 1,
        ],
        'use_customer_segment' => [
            'All' => 0,
            'Specified' => 1,
        ],
    ];

    /**
     * Url for save rewrite.
     *
     * @var string
     */
    protected $url = 'admin/banner/save/back/edit/active_tab/content_section/';

    /**
     * Catalog rules.
     *
     * @var string
     */
    protected $catalogRules = '';

    /**
     * Sales rules.
     *
     * @var string
     */
    protected $salesRules = '';

    /**
     * Post request for creating banner.
     *
     * @param FixtureInterface|null $fixture [optional]
     * @return array
     * @throws \Exception
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $url = $_ENV['app_backend_url'] . $this->url;
        $data = $this->replaceMappingData($fixture->getData());
        if (isset($data['banner_catalog_rules'])) {
            foreach ($data['banner_catalog_rules'] as $key => $catalogRule) {
                $this->catalogRules = $catalogRule;
                if ($key > 0) {
                    $this->catalogRules .= '&';
                }
            }
            $data['banner_catalog_rules'] = $this->catalogRules;
        } elseif (isset($data['banner_sales_rules'])) {
            foreach ($data['banner_sales_rules'] as $key => $salesRule) {
                $this->salesRules = $salesRule;
                if ($key > 0) {
                    $this->salesRules .= '&';
                }
            }
            $data['banner_sales_rules'] = $this->salesRules;
        }
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->write($url, $data);
        $response = $curl->read();

        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception("Banner creation by curl handler was not successful! Response: $response");
        }
        $curl->close();
        preg_match("~\/id\/(\d*?)\/~", $response, $matches);
        $id = isset($matches[1]) ? $matches[1] : null;
        return ['banner_id' => $id];
    }

    /**
     * Replace mapping data in fixture data.
     *
     * @param array $data
     * @return array
     */
    protected function replaceMappingData(array $data)
    {
        if (isset($data['store_contents_not_use'])) {
            foreach ($data['store_contents_not_use'] as $key => $storeContent) {
                $store = explode('_', $key);
                $data["store_contents_not_use[{$store[1]}]"] = $this
                    ->mappingData['store_contents_not_use'][$storeContent];
            }
            unset($data['store_contents_not_use']);
        }
        if (isset($data['store_contents'])) {
            foreach ($data['store_contents'] as $key => $storeContent) {
                $store = explode('_', $key);
                $data["store_contents[{$store[1]}]"] = $storeContent;
            }
            unset($data['store_contents']);
        }
        if (isset($data['customer_segment_ids'])) {
            foreach ($data['customer_segment_ids'] as $key => $customerSegment) {
                $data["customer_segment_ids[{$key}]"] = $customerSegment;
            }
            unset($data['customer_segment_ids']);
        }

        return parent::replaceMappingData($data);
    }
}
