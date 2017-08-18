<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 * @magentoDataFixture Magento/SalesRule/_files/rules_category.php
 */
class FilterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider insertRuleFilterDataProvider
     * @param $data
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function testInsertRuleFilters($data)
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Category');

        foreach ($data as $key => $tmp) {
            $data[$key]['rule_id'] = $rule->getRuleId();
        }

        /** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter $filter */
        $filter = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter::class
        );

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()->where('rule_id = ?', $rule->getRuleId())->from($filter->getMainTable());

        $origCount = $filtersSelect->query()->rowCount();

        $this->assertTrue($filter->insertFilters($data));

        $items = $filtersSelect->query()->fetchAll();
        $this->assertEquals($origCount + count($data), count($items));
        $this->assertEquals($data[0], $items[count($items) - 2]);
        $this->assertEquals($data[1], $items[count($items) - 1]);
    }

    /**
     */
    public function testDeleteRuleFilters()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Category');

        /** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter $filter */
        $filter = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter::class
        );

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()->where('rule_id = ?', $rule->getRuleId())->from($filter->getMainTable());

        $origItemsCount = $filtersSelect->query()->rowCount();
        $this->assertGreaterThan(0, $origItemsCount, 'testDeleteRuleFilters expects at least 1 filter');

        $this->assertTrue($filter->deleteRuleFilters([$rule->getRuleId()]));

        $itemsCount = $filtersSelect->query()->rowCount();
        $this->assertEquals(0, $itemsCount);
    }

    /**
     * @return array
     */
    public function insertRuleFilterDataProvider()
    {
        return [
            '2_categories_one_group' => [
                [
                    [
                        'rule_id' => null,
                        'group_id' => 2,
                        'weight' => 0.5,
                        'filter_text' => 'product:category:2',
                        'filter_text_generator_class' =>
                            \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                        'filter_text_generator_arguments' => '[]'
                    ],
                    [
                        'rule_id' => null,
                        'group_id' => 2,
                        'weight' => 0.5,
                        'filter_text' => 'product:category:3',
                        'filter_text_generator_class' =>
                            \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                        'filter_text_generator_arguments' => '[]'
                    ],
                ]
            ]
        ];
    }

    /**
     * @dataProvider getFilterTextGeneratorsDataProvider
     * @param $class
     * @param $arguments
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_not_categories_sku_attr.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_all_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_any_categories_price_attr_set_any.php
     */
    public function testGetFilterTextGenerators($class, $arguments)
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter $filter */
        $filter = $objectManager->create(
            \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter::class
        );

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()->from($filter->getMainTable());

        $availableFilters = $filtersSelect->query()->fetchAll();

        //testing that null values are available
        $nullCnt = 0;
        foreach ($availableFilters as $filterElem) {
            foreach ($filterElem as $filterVal) {
                if ($filterVal === null) {
                    $nullCnt++;
                }
            }
        }
        $this->assertGreaterThan(0, $nullCnt, 'Test requires that at least one of the rules filtered have null values');

        $filteredArray = $filter->getFilterTextGenerators();
        //testing that no null filter went through
        foreach ($filteredArray as $filteredElem) {
            foreach ($filteredElem as $filtered) {
                if ($filtered === null) {
                    $this->fail('Filtering null values are not accepted');
                }
            }
        }

        $this->assertTrue(
            $this->isInArgumentArrayOnce($class . $arguments, $filteredArray),
            'Failing asserting ' . $class . ' with ' . $arguments . ' doesn\'t exist or not unique in filters the array'
        );
    }

    /**
     * @return array
     */
    public function getFilterTextGeneratorsDataProvider()
    {
        return [
            'attr_set' =>  [
                'filter_text_generator_class' =>
                    \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Attribute::class,
                'filter_text_generator_arguments' => '{"attribute":"attribute_set_id"}',
            ],
            'sku' =>  [
                'filter_text_generator_class' =>
                    \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Attribute::class,
                'filter_text_generator_arguments' => '{"attribute":"sku"}',
            ],
            'category' =>  [
                'filter_text_generator_class' =>
                    \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                'filter_text_generator_arguments' => '[]',
            ]
        ];
    }

    /**
     * check if filter string is in array
     * @param string $filterString
     * @param array $filterArray
     * @return bool
     */
    protected function isInArgumentArrayOnce($filterString, $filterArray)
    {
        $count = 0;
        if (is_array($filterArray)) {
            foreach ($filterArray as $filterElem) {
                if (isset($filterElem['filter_text_generator_class']) &&
                    isset($filterElem['filter_text_generator_arguments'])
                ) {
                    $conc = $filterElem['filter_text_generator_class'] . $filterElem['filter_text_generator_arguments'];
                    if ($conc == $filterString) {
                        $count++;
                    }
                }
            }
        }
        return $count == 1;
    }

    /**
     * @dataProvider filterRulesDataProvider
     * @param array $inputArray
     * @param int $count
     * @magentoDataFixture Magento/AdvancedSalesRule/_files/delete_salesrule_filters.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_not_categories_sku_attr.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_all_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_any_categories_price_attr_set_any.php
     */
    public function testFilterRules($inputArray, $count)
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter $filter */
        $filter = $objectManager->create(
            \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter::class
        );

        $this->assertEquals($count, count($filter->filterRules($inputArray)));
    }

    /**
     * @return array
     */
    public function filterRulesDataProvider()
    {
        return [
            'true_set' =>  [
                'array' =>
                    [
                        'true',
                        'product:attribute:sku:prod1',
                    ],
                    'count' => 1
            ],
            'one_cat_set' =>  [
                'array' =>
                    [
                        'product:attribute:sku:prod1',
                        'product:category:3',
                    ],
                    'count' => 1
            ],
            'cat_true_set' =>  [
                'array' =>
                    [
                        'true',
                        'product:category:2',
                        'product:category:3',
                        'product:category:4',
                    ],
                    'count' => 3
            ],
            'three_cat_set' =>  [
                'array' =>
                    [
                        'product:category:2',
                        'product:category:3',
                        'product:category:4',
                    ],
                    'count' => 2
            ],

        ];
    }
}
