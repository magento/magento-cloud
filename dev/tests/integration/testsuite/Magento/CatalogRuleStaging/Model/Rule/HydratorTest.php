<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogRuleStaging\Model\Rule;

use Magento\CatalogRule\Model\Rule;

class HydratorTest extends \PHPUnit\Framework\TestCase
{
    /** @var  Hydrator */
    private $hydrator;

    /** @var  \Magento\Framework\ObjectManagerInterface */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $retriever = $this->objectManager->get(\Magento\CatalogRuleStaging\Model\Rule\Retriever::class);
        $this->hydrator = $this->objectManager->create(Hydrator::class, [
            'entityRetriever' => $retriever
        ]);
    }

    /**
     * @magentoAppArea adminhtml
     *
     * @param array $variation
     * @dataProvider hydrateDataProvider
     */
    public function testHydrate($variation)
    {
        $dataSet = [
            'name' => 'Test',
            'conditions_serialized' => '{"type":"Magento\\CatalogRule\\Model\\Rule\\Condition\\Combine",'
                . '"attribute":null,"operator":null,"value":"1","is_value_processed":null,"aggregator":"all"}',
            'actions_serialized' => '{"type":"Magento\\CatalogRule\\Model\\Rule\\Action\\Collection",'
                . '"attribute":null,"operator":"=","value":null}',
            'is_active' => 1,
            'website_ids' => [1],
            'customer_group_ids' => [0],
            'staging' => [],
            'created_in' => time(),
            'updated_in' => 2147483647,
            'discount_amount' => 50,
            'simple_action' => 'by_percent'
        ];
        $data = array_merge($dataSet, $variation);
        $field = key($variation);
        $expectedValue = current($variation);

        /** @var Rule $rule */
        $rule = $this->hydrator->hydrate($data);
        $this->assertEquals($expectedValue, $rule->getData($field));
    }

    public function hydrateDataProvider()
    {
        return [
            [
                [
                    'is_active' => 1,
                ]
            ],
            [
                [
                    'is_active' => 0
                ]
            ],
        ];
    }
}
