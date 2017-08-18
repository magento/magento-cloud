<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Handler\GiftWrapping;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Class Curl
 * Curl handler for creating gift wrapping
 */
class Curl extends AbstractCurl implements GiftWrappingInterface
{
    /**
     * Mapping for gift wrapping data
     *
     * @var array
     */
    protected $mappingData = [
        'status' => [
            'Disabled' => 0,
            'Enabled' => 1,
        ],
    ];

    /**
     * Post request for creating gift wrapping
     *
     * @param FixtureInterface|null $fixture [optional]
     * @return array|mixed
     * @throws \Exception
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $data['wrapping'] = $this->prepareData($fixture);
        $url = $_ENV['app_backend_url'] . 'admin/giftwrapping/save/store/0/back/edit/';
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->addOption(CURLOPT_HEADER, 1);
        $curl->write($url, $data);
        $response = $curl->read();
        $curl->close();

        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception("Gift Wrapping creation by curl handler was not successful! Response: $response");
        }

        preg_match("~Location: [^\\s]*giftwrapping\\/edit\\/id\\/(\\d+)~", $response, $matches);
        $id = isset($matches[1]) ? $matches[1] : null;
        return ['wrapping_id' => $id];
    }

    /**
     * Prepare data from text to values
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    protected function prepareData(FixtureInterface $fixture)
    {
        $data = $this->replaceMappingData($fixture->getData());
        $websites = $fixture->getDataFieldConfig('website_ids')['source']->getWebsites();
        foreach ($websites as $key => $website) {
            $data['website_ids'][$key] = $website->getWebsiteId();
        }

        return $data;
    }
}
