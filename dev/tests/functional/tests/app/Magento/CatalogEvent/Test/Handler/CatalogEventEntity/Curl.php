<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Handler\CatalogEventEntity;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Class Curl
 * Create Catalog Event
 */
class Curl extends AbstractCurl implements CatalogEventEntityInterface
{
    /**
     * Post request for creating Event
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $data = ['catalogevent' => $fixture->getData()];

        if ($data['catalogevent']['display_state']['category_page'] === 'Yes') {
            $data['catalogevent']['display_state']['category_page'] = 1;
        }
        if ($data['catalogevent']['display_state']['product_page'] === 'Yes') {
            $data['catalogevent']['display_state']['product_page'] = 2;
        }
        $data['catalogevent']['display_state'] = array_values($data['catalogevent']['display_state']);
        if ($fixture->getDataFieldConfig('category_id')['source']->getCategory() === null) {
            $categoryId = $fixture->getCategoryId();
        } else {
            $categoryId = $fixture->getDataFieldConfig('category_id')['source']->getCategory()->getId();
        }
        $url = $_ENV['app_backend_url'] . 'admin/catalog_event/save/category_id/'
            . $categoryId . '/category/';
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->write($url, $data);
        $response = $curl->read();
        $curl->close();
        preg_match(
            '/class=\"\scol\-id col\-event_id\W*>\W+(\d+)\W+<\/td>\W+<td[\w\s\"=\-]*?>\W+?'
            . $categoryId . '/siu',
            $response,
            $matches
        );
        $id = isset($matches[1]) ? $matches[1] : null;

        return ['id' => $id];
    }
}
