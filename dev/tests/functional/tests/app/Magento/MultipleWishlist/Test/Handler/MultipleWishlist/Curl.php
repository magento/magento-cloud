<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Handler\MultipleWishlist;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlInterface;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\FrontendDecorator;

/**
 * Create new multiple wish list via curl.
 */
class Curl extends AbstractCurl implements MultipleWishlistInterface
{
    /**
     * Mapping values for data.
     *
     * @var array
     */
    protected $mappingData = [
        'visibility' => [
            'Yes' => 'on',
            'No' => 'off',
        ],
    ];

    /**
     * Customer fixture.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * Post request for creating multiple wish list.
     *
     * @param FixtureInterface|null $fixture [optional]
     * @return array
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $this->customer = $fixture->getDataFieldConfig('customer_id')['source']->getCustomer();
        $data = $this->replaceMappingData($this->prepareData($fixture));
        return ['id' => $this->createWishlist($data)];
    }

    /**
     * Prepare POST data for creating product request.
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    protected function prepareData(FixtureInterface $fixture)
    {
        $data = $fixture->getData();
        unset($data['customer_id']);
        return $data;
    }

    /**
     * Create product via curl.
     *
     * @param array $data
     * @return void
     */
    protected function createWishlist(array $data)
    {
        $url = $_ENV['app_frontend_url'] . 'wishlist/index/createwishlist/';
        $curl = new FrontendDecorator(new CurlTransport(), $this->customer);
        $curl->write($_ENV['app_frontend_url'] . 'wishlist', [], CurlInterface::GET);
        $curl->read();
        $curl->addOption(CURLOPT_HEADER, 1);
        $curl->write($url, $data);
        $curl->read();
        $curl->close();
        // TODO: add verification to check whether multiple wih list has been created or not and return its id
    }
}
