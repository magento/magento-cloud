<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Handler\GiftRegistryType;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Class Curl
 * Curl handler for creating Gift Registry Type
 */
class Curl extends AbstractCurl implements GiftRegistryTypeInterface
{
    /**
     * Url for saving data
     *
     * @var string
     */
    protected $saveUrl = 'admin/giftregistry/save/store/0/back/edit/active_tab/general_section/';

    /**
     * Mapping values for data
     *
     * @var array
     */
    protected $mappingData = [
        'is_listed' => [
            'Yes' => 1,
            'No' => 0,
        ],
        'group' => [
            'Event Information' => 'event_information',
            'Gift Registry Details' => 'registry',
            'Privacy Settings' => 'privacy',
            'Invitee Information' => 'registrant',
            'Shipping Address' => 'shipping',
        ],
        'type' => [
            'Custom Types/Text' => 'text',
            'Custom Types/Select' => 'select',
            'Custom Types/Date' => 'date',
            'Custom Types/Country' => 'country',
            'Static Types/Event Date' => 'event_date',
            'Static Types/Event Country' => 'event_country',
            'Static Types/Event Location' => 'event_location',
            'Static Types/Role' => 'role',
        ],
        'is_required' => [
            'Yes' => 1,
            'No' => 0,
        ],
    ];

    /**
     * POST request for creating gift registry type
     *
     * @param FixtureInterface|null $fixture [optional]
     * @throws \Exception
     * @return array
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $data = $this->prepareData($fixture);
        $url = $_ENV['app_backend_url'] . $this->saveUrl;
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->write($url, $data);
        $response = $curl->read();
        $curl->close();
        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception("Gift registry type creating by curl handler was not successful! Response: $response");
        }

        preg_match('@/delete/id/(\d+)/.*Delete@ms', $response, $matches);
        return ['type_id' => $matches[1]];
    }

    /**
     * Prepare data for CURL request
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    protected function prepareData($fixture)
    {
        $data = $this->replaceMappingData($fixture->getData());
        $preparedData = [];
        foreach ($data as $key => $value) {
            if ($key != 'attributes') {
                $preparedData['type'][$key] = $value;
            } else {
                $preparedData['attributes']['registry'] = $data[$key];
                foreach ($preparedData['attributes']['registry'] as &$attribute) {
                    $attribute = $this->prepareAttributes($attribute);
                }
            }
        }
        return $preparedData;
    }

    /**
     * Preparing attributes array for curl response
     *
     * @param array $attribute
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function prepareAttributes(array $attribute)
    {
        $attribute['frontend']['is_required'] = $attribute['is_required'];
        unset($attribute['is_required']);
        $attribute['is_deleted'] = '';
        if (isset($attribute['options'])) {
            foreach ($attribute['options'] as $key => $option) {
                $attribute['options'][$key]['is_deleted'] = '';
            }
        }
        return $attribute;
    }
}
