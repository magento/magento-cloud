<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\Handler\TargetRule;

use Magento\Backend\Test\Handler\Conditions;
use Magento\TargetRule\Test\Fixture\TargetRule;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Curl handler for creating target rule through backend.
 */
class Curl extends Conditions implements TargetRuleInterface
{
    /**
     * Map of type parameter.
     *
     * @var array
     */
    protected $mapTypeParams = [
        'Conditions combination' => [
            'type' => \Magento\TargetRule\Model\Rule\Condition\Combine::class,
            'aggregator' => 'all',
            'value' => 1,
        ],
        'Attribute Set' => [
            'type' => \Magento\TargetRule\Model\Rule\Condition\Product\Attributes::class,
            'attribute' => 'attribute_set_id',
        ],
        'Price (percentage)' => [
            'type' => \Magento\TargetRule\Model\Actions\Condition\Product\Special\Price::class,
        ],
        'Category' => [
            'type' => \Magento\TargetRule\Model\Rule\Condition\Product\Attributes::class,
            'attribute' => 'category_ids',
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
            'Related Products' => 1,
            'Up-sells' => 2,
            'Cross-sells' => 3,
        ],
        'use_customer_segment' => [
            'All' => 0,
            'Specified' => 1,
        ],
    ];

    /**
     * Post request for creating target rule in backend.
     *
     * @param FixtureInterface|null $targetRule
     * @return array
     * @throws \Exception
     */
    public function persist(FixtureInterface $targetRule = null)
    {
        /** @var TargetRule $targetRule */
        $url = $_ENV['app_backend_url']
            . 'admin/targetrule/save/back/edit/active_tab/magento_targetrule_edit_tab_main/';
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $data = $this->replaceMappingData($targetRule->getData());
        $data = $this->prepareData($data);

        $curl->write($url, $data);
        $response = $curl->read();
        $curl->close();
        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            $this->_eventManager->dispatchEvent(['curl_failed'], [$response]);
            throw new \Exception("Target rule was not created! See curl response in the logs.");
        }

        return ['rule_id' => $this->getTargetRuleId($response)];
    }

    /**
     * Prepare target rule cURL request data.
     *
     * @param array $data
     * @return array
     */
    private function prepareData(array $data)
    {
        if (!isset($data['conditions_serialized'])) {
            $data['rule']['conditions'] = '';
        } else {
            $data['rule']['conditions'] = $this->prepareCondition($data['conditions_serialized']);
        }
        unset($data['conditions_serialized']);
        if (!isset($data['actions_serialized'])) {
            $data['actions_serialized'] = '';
        }
        $data['rule']['actions'] = $this->prepareCondition($data['actions_serialized']);
        unset($data['actions_serialized']);

        if (!isset($data['from_date'])) {
            $data['from_date'] = '';
        }
        if (!isset($data['to_date'])) {
            $data['to_date'] = '';
        }

        return $data;
    }

    /**
     * Get target rule id from response.
     *
     * @param string $response
     * @return int|null
     */
    protected function getTargetRuleId($response)
    {
        preg_match('/targetrule\/delete\/id\/([0-9]+)/', $response, $match);
        return empty($match[1]) ? null : $match[1];
    }
}
