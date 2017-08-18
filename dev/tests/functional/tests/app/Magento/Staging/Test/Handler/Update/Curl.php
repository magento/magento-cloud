<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Handler\Update;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;
use Magento\Catalog\Test\Handler\CatalogProductSimple\Curl as ProductCurl;
use Magento\Mtf\Config\DataInterface;
use Magento\Mtf\System\Event\EventManagerInterface;
use Magento\Mtf\Util\Protocol\CurlInterface;

/**
 * Curl handler for creating update campaign.
 */
class Curl extends AbstractCurl implements UpdateInterface
{
    /**
     * Product curl handler.
     *
     * @var ProductCurl
     */
    protected $productCurl;

    /**
     * Update id.
     *
     * @var int
     */
    protected $updateId;

    /**
     * @constructor
     * @param DataInterface $configuration
     * @param EventManagerInterface $eventManager
     * @param ProductCurl $productCurl
     */
    public function __construct(
        DataInterface $configuration,
        EventManagerInterface $eventManager,
        ProductCurl $productCurl
    ) {
        parent::__construct($configuration, $eventManager);
        $this->productCurl = $productCurl;
    }

    /**
     * POST request for creating update campaign.
     *
     * @param FixtureInterface|null $fixture [optional]
     * @throws \Exception
     * @return array
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $data = $this->prepareData($fixture);

        foreach ($this->prepareUrl($fixture, $data) as $url) {
            $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
            $curl->addOption(CURLOPT_HEADER, 1);
            if (isset($this->updateId)) {
                $data['update_id'] = $this->updateId;
            }
            $curl->write(
                $url,
                [
                    'staging' => $data,
                    $data['entity_type'] => $this->prepareEntityData($data)
                ]
            );
            $response = $curl->read();
            $curl->close();

            if (!strpos($response, '"error":false')) {
                $this->_eventManager->dispatchEvent(['curl_failed'], [$response]);
            }
            $this->updateId = $this->getUpdateId($data['name']);
        }

        return ['staging' => $fixture];
    }

    /**
     * Get update id by name.
     *
     * @param string $name
     * @return int|null
     */
    protected function getUpdateId($name)
    {
        $url = $_ENV['app_backend_url'] . 'mui/index/render/';
        $data = [
            'namespace' => 'staging_update_grid',
            'filters' => [
                'placeholder' => true,
                'name' => $name
            ],
            'isAjax' => true
        ];
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);

        $curl->write($url, $data, CurlInterface::POST);
        $response = $curl->read();
        $curl->close();
        preg_match('/staging_update_grid_data_source.+items.+"id":"(\d+)"/', $response, $match);

        return empty($match[1]) ? null : $match[1];
    }

    /**
     * Prepare URL.
     *
     * @param FixtureInterface $fixture
     * @param array $data
     * @return array
     */
    private function prepareUrl(FixtureInterface $fixture, array $data)
    {
        $url = [];
        foreach ($fixture->getDataFieldConfig($data['entity_type'])['source']->getUrl() as $urlPart) {
            $url[] = $_ENV['app_backend_url'] . $urlPart;
        }
        return $url;
    }

    /**
     * Prepare data for CURL request.
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    protected function prepareData($fixture)
    {
        $data = $fixture->getData();
        $data['mode'] = 'save';
        $data['end_time'] = isset($data['end_time']) ? $data['end_time'] : '';
        return $data;
    }

    /**
     * Prepare entity data for request.
     *
     * @param array $data
     * @return array
     */
    private function prepareEntityData(array $data)
    {
        $entityData = [];
        switch ($data['entity_type']) {
            case 'product':
                $entityData = $this->productCurl->prepareData($data[$data['entity_type']])['product'];
                $entityData['is_new'] = 0;
                break;
            default:
                break;
        }
        return $entityData;
    }
}
