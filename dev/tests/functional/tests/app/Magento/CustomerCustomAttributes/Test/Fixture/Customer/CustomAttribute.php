<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Fixture\Customer;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Source for attribute field.
 */
class CustomAttribute extends DataSource
{
    /**
     * @constructor
     * @param array $params
     * @param mixed $data
     * @param FixtureFactory $fixtureFactory
     */
    public function __construct(array $params, $data, FixtureFactory $fixtureFactory)
    {
        $this->params = $params;
        if (!isset($data['attribute_id']) && !isset($data['attribute'])) {
            /** @var CustomerCustomAttribute $customerCustomAttribute*/
            $customerCustomAttribute = $fixtureFactory->createByCode(
                'customerCustomAttribute',
                ['data' => $data]
            );
            $customerCustomAttribute->persist();
            $data = $customerCustomAttribute->getData();
            $this->data['value'] = $data['options'];
            $this->data['code'] = $data['attribute_code'];
        } elseif (is_array($data)) {
            $this->data['value'] = isset($data['value']) ? $data['value'] : null;
            $this->data['code'] = isset($data['attribute']) && $data['attribute'] instanceof CustomerCustomAttribute
                ? $data['attribute']->getAttributeCode() : null;
        } else {
            $this->data['value'] = $data;
        }
    }
}
