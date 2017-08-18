<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Handler\CustomerSegment;

use Magento\Backend\Test\Handler\Conditions;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Util\Protocol\CurlInterface;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Curl handler for creating customer segment through backend.
 */
class Curl extends Conditions implements CustomerSegmentInterface
{
    /**
     * Map of type parameter.
     *
     * @var array
     */
    protected $mapTypeParams = [
        'Conditions combination' => [
            'type' => \Magento\CustomerSegment\Model\Segment\Condition\Combine\Root::class,
            'aggregator' => 'all',
            'value' => 1,
        ],
        'Default Billing Address' => [
            'type' => \Magento\CustomerSegment\Model\Segment\Condition\Customer\Attributes::class,
            'attribute' => 'default_billing',
        ],
        'Default Shipping Address' => [
            'type' => \Magento\CustomerSegment\Model\Segment\Condition\Customer\Attributes::class,
            'attribute' => 'default_shipping',
        ],
        'Group' => [
            'type' => \Magento\CustomerSegment\Model\Segment\Condition\Customer\Attributes::class,
            'attribute' => 'group_id',
        ],
    ];

    /**
     * Map of rule parameters.
     *
     * @var array
     */
    protected $mapRuleParams = [
        'operator' => [
            'is' => '==',
        ],
        'value' => [
            'exists' => 'is_exists',
            'General' => '1',
            'Wholesale' => '2',
            'Retailer' => '3'
        ],
    ];

    /**
     * Mapping values for data.
     *
     * @var array
     */
    protected $mappingData = [
        'is_active' => [
            'Active' => 1,
            'Inactive' => 0,
        ],
        'apply_to' => [
            'Visitors and Registered Customers' => 0,
            'Registered Customers' => 1,
            'Visitors' => 2,
        ],
    ];

    /**
     * Post request for creating customer segment in backend.
     *
     * @param FixtureInterface|null $customerSegment
     * @return array
     * @throws \Exception
     */
    public function persist(FixtureInterface $customerSegment = null)
    {
        /** @var CustomerSegment $customerSegment */
        $url = $_ENV['app_backend_url'] . 'customersegment/index/save/back/edit/active_tab/general_section';
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $data = $this->replaceMappingData($customerSegment->getData());

        if ($customerSegment->hasData('conditions_serialized')) {
            $data['rule']['conditions'] = $this->prepareCondition($data['conditions_serialized']);
            unset($data['conditions_serialized']);
        }

        $data['website_ids'] = $this->getWebsiteIdsValue($data['website_ids']);
        $curl->write($url, $data);
        $response = $curl->read();
        $curl->close();
        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception(
                "CustomerSegment entity creating by curl handler was not successful!" . " Response: $response"
            );
        }

        return ['segment_id' => $this->getCustomerSegmentId($response)];
    }

    /**
     * Get "website_ids" values by names.
     *
     * @param array|string $names
     * @return array
     * @throws \Exception
     */
    protected function getWebsiteIdsValue($names)
    {
        $url = $_ENV['app_backend_url'] . 'admin/system_store';
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $result = [];

        $curl->write($url, [], CurlInterface::GET);
        $response = $curl->read();
        $curl->close();

        $names = is_array($names) ? $names : [$names];
        foreach ($names as $name) {
            preg_match(
                '/<a[^>]+href="[^"]+website_id\\/([\\d]+)\\/"[^>]*>' . preg_quote($name) . '<\\/a>/',
                $response,
                $match
            );
            if (!isset($match[1])) {
                throw new \Exception("Can't find website id by name \"{$name}\". Response: $response");
            }

            $result[] = $match[1];
        }

        return $result;
    }

    /**
     * Get customer segment id from response.
     *
     * @param string $response
     * @return int|null
     */
    protected function getCustomerSegmentId($response)
    {
        preg_match('/customersegment\/index\/delete\/id\/([0-9]+)/', $response, $match);
        return empty($match[1]) ? null : $match[1];
    }
}
