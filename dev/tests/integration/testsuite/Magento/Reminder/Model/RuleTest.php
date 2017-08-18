<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Model;

use \Magento\TestFramework\Helper\Bootstrap;
use \Magento\Framework\App\ObjectManager;
use \Magento\Framework\Serialize\Serializer\Json;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class RuleTest extends \PHPUnit\Framework\TestCase
{
    public function testSerializedConditionsInRules()
    {
        $conditionsValue = 555;

        $conditions = [
            'type' => \Magento\Reminder\Model\Rule\Condition\Combine\Root::class,
            'attribute' => null,
            'operator' => null,
            'value' => $conditionsValue,
            'is_value_processed' => null,
            'aggregator' => 'all'
        ];

        /** @var $rule \Magento\Reminder\Model\Rule */
        $rule = Bootstrap::getObjectManager()->create(\Magento\Reminder\Model\Rule::class);

        /** @var $serializer \Magento\Framework\Serialize\Serializer\Json */
        $serializer = ObjectManager::getInstance()->create(Json::class);

        $serializedConditions = $serializer->serialize($conditions);

        $rule->setData(
            [
                'name' => 'Serialized Rule',
                'conditions_serialized' => $serializedConditions
            ]
        )->save();

        $collection = Bootstrap::getObjectManager()->create(
            \Magento\Reminder\Model\ResourceModel\Rule\Collection::class
        );

        foreach ($collection as $rule) {
            $this->assertInstanceOf(\Magento\Reminder\Model\Rule\Condition\Combine\Root::class, $rule->getConditions());
            $this->assertEquals($conditionsValue, $rule->getConditions()->getValue());
            return;
        }
    }
}
