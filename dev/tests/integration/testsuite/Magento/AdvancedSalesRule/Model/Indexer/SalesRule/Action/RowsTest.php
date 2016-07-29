<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedSalesRule\Model\Indexer\SalesRule\Action;

/**
 * @magentoDataFixtureBeforeTransaction Magento/AdvancedSalesRule/_files/salesrule_indexer_on_schedule.php
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class RowsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/SalesRule/_files/rules_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_category.php
     */
    public function testFilterRuleSaveOnSchedule()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule1 = $objectManager->get('Magento\Framework\Registry')
            ->registry('_fixture/Magento_SalesRule_Multiple_Categories');

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule2 = $objectManager->get('Magento\Framework\Registry')
            ->registry('_fixture/Magento_SalesRule_Category');

        /** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter $filter */
        $filter = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter'
        );

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()
            ->from($filter->getMainTable())
            ->where('rule_id in (?)', [$rule1->getRuleId(), $rule2->getRuleId()]);
        $items = $filtersSelect->query()->fetchAll();

        //test with save on schedule that prevents saving on save
        $this->assertTrue(empty($items));

        /** @var \Magento\AdvancedSalesRule\Model\Indexer\SalesRule\Action\Rows $action */
        $action = $objectManager->create('Magento\AdvancedSalesRule\Model\Indexer\SalesRule\Action\Rows');
        $action->execute([$rule1->getRuleId(), $rule2->getRuleId()]);

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()->from($filter->getMainTable());
        $items = $filtersSelect->query()->fetchAll();

        //test if execute generated all filters
        $this->assertEquals(
            [
                [
                    'rule_id' => $rule1->getRuleId(),
                    'group_id' => '1',
                    'weight' => '0.5',
                    'filter_text' => 'product:category:2',
                    'filter_text_generator_class' =>
                        'Magento\\AdvancedSalesRule\\Model\\Rule\\Condition\\FilterTextGenerator\\Product\\Category',
                    'filter_text_generator_arguments' => '[]',
                ],
                [
                    'rule_id' => $rule1->getRuleId(),
                    'group_id' => '1',
                    'weight' => '0.5',
                    'filter_text' => 'product:category:3',
                    'filter_text_generator_class' =>
                        'Magento\\AdvancedSalesRule\\Model\\Rule\\Condition\\FilterTextGenerator\\Product\\Category',
                    'filter_text_generator_arguments' => '[]',
                ],
                [
                    'rule_id' => $rule2->getRuleId(),
                    'group_id' => '1',
                    'weight' => '1',
                    'filter_text' => 'product:category:66',
                    'filter_text_generator_class' =>
                        'Magento\\AdvancedSalesRule\\Model\\Rule\\Condition\\FilterTextGenerator\\Product\\Category',
                    'filter_text_generator_arguments' => '[]',
                ]
            ],
            $items
        );
    }
}
