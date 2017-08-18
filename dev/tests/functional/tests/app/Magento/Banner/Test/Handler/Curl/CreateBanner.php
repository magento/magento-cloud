<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Handler\Curl;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Curl handler for creating a banner
 *
 */
class CreateBanner extends Curl
{
    /**
     * Post request for creating banner
     *
     * @param FixtureInterface $fixture [optional]
     * @throws \Exception
     * @return null|string banner_id
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $response = $this->postRequest($fixture);
        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception('Banner creation by curl handler was not successful! Response: ' . $response);
        }
        $bannerId = null;
        if (preg_match('/\/banner\/edit\/id\/(\d+)/', $response, $matches)) {
            $bannerId = $matches[1];
        }
        return $bannerId;
    }

    /**
     * Post request for creating banner
     *
     * @param FixtureInterface $fixture [optional]
     * @return string
     */
    protected function postRequest(FixtureInterface $fixture = null)
    {
        $data = $fixture->getData('fields');
        $fields = [];
        foreach ($data as $key => $field) {
            $fields[$key] = $field['value'];
        }
        $url = $_ENV['app_backend_url'] . 'admin/banner/save/';
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->write($url, $fields);
        $response = $curl->read();
        $curl->close();

        return $response;
    }
}
