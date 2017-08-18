<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedSalesRule\Model\Indexer;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class SalesRuleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Reindex on save is on, we're rebuilding only specified ids
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/SalesRule/_files/rules_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_category.php
     */
    public function testExecute()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var \Magento\AdvancedSalesRule\Model\Indexer\SalesRule $indexer */
        $indexer = $objectManager->create(\Magento\AdvancedSalesRule\Model\Indexer\SalesRule::class);

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule1 = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Multiple_Categories');

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule2 = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Category');

        /** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter $filter */
        $filter = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter::class
        );

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()
            ->from(['e' => $filter->getMainTable()])
            ->where('rule_id in (?)', [$rule1->getRuleId(), $rule2->getRuleId()]);
        $sql = $filtersSelect->deleteFromSelect('e');
        $connection->query($sql);

        $indexer->execute([$rule1->getRuleId()]);

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()
            ->from(['e' => $filter->getMainTable()])
            ->where('rule_id in (?)', [$rule1->getRuleId(), $rule2->getRuleId()]);
        $items = $filtersSelect->query()->fetchAll();

        $this->assertEquals(
            [
                [
                    'rule_id' => $rule1->getRuleId(),
                    'group_id' => '1',
                    'weight' => '0.5',
                    'filter_text' => 'product:category:2',
                    'filter_text_generator_class' =>
                        \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                    'filter_text_generator_arguments' => '[]',
                ],
                [
                    'rule_id' => $rule1->getRuleId(),
                    'group_id' => '1',
                    'weight' => '0.5',
                    'filter_text' => 'product:category:3',
                    'filter_text_generator_class' =>
                        \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                    'filter_text_generator_arguments' => '[]',
                ]
            ],
            $items
        );
    }

    /**
     * Reindex on save is on, we're rebuilding only specified ids
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/SalesRule/_files/rules_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_category.php
     */
    public function testExecuteList()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var \Magento\AdvancedSalesRule\Model\Indexer\SalesRule $indexer */
        $indexer = $objectManager->create(\Magento\AdvancedSalesRule\Model\Indexer\SalesRule::class);

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule1 = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Multiple_Categories');

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule2 = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Category');

        /** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter $filter */
        $filter = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter::class
        );

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()
            ->from(['e' => $filter->getMainTable()])
            ->where('rule_id in (?)', [$rule1->getRuleId(), $rule2->getRuleId()]);
        $sql = $filtersSelect->deleteFromSelect('e');
        $connection->query($sql);

        $indexer->executeList([$rule1->getRuleId()]);

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()
            ->from(['e' => $filter->getMainTable()])
            ->where('rule_id in (?)', [$rule1->getRuleId(), $rule2->getRuleId()]);
        $items = $filtersSelect->query()->fetchAll();
        $this->assertEquals(
            [
                [
                    'rule_id' => $rule1->getRuleId(),
                    'group_id' => '1',
                    'weight' => '0.5',
                    'filter_text' => 'product:category:2',
                    'filter_text_generator_class' =>
                        \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                    'filter_text_generator_arguments' => '[]',
                ],
                [
                    'rule_id' => $rule1->getRuleId(),
                    'group_id' => '1',
                    'weight' => '0.5',
                    'filter_text' => 'product:category:3',
                    'filter_text_generator_class' =>
                        \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                    'filter_text_generator_arguments' => '[]',
                ]
            ],
            $items
        );
    }

    /**
     * Reindex on save is on, we're rebuilding 1 row
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/SalesRule/_files/rules_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_category.php
     */
    public function testExecuteRow()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var \Magento\AdvancedSalesRule\Model\Indexer\SalesRule $indexer */
        $indexer = $objectManager->create(\Magento\AdvancedSalesRule\Model\Indexer\SalesRule::class);

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule1 = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Multiple_Categories');

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule2 = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Category');

        /** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter $filter */
        $filter = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter::class
        );

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()
            ->from(['e' => $filter->getMainTable()])
            ->where('rule_id in (?)', [$rule1->getRuleId(), $rule2->getRuleId()]);
        $sql = $filtersSelect->deleteFromSelect('e');
        $connection->query($sql);

        $indexer->executeRow($rule1->getRuleId());

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()
            ->from(['e' => $filter->getMainTable()])
            ->where('rule_id in (?)', [$rule1->getRuleId(), $rule2->getRuleId()]);
        $items = $filtersSelect->query()->fetchAll();
        $this->assertEquals(
            [
                [
                    'rule_id' => $rule1->getRuleId(),
                    'group_id' => '1',
                    'weight' => '0.5',
                    'filter_text' => 'product:category:2',
                    'filter_text_generator_class' =>
                        \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                    'filter_text_generator_arguments' => '[]',
                ],
                [
                    'rule_id' => $rule1->getRuleId(),
                    'group_id' => '1',
                    'weight' => '0.5',
                    'filter_text' => 'product:category:3',
                    'filter_text_generator_class' =>
                        \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                    'filter_text_generator_arguments' => '[]',
                ]
            ],
            $items
        );
    }

    /**
     * Reindex on save is on, we're rebuilding all rows
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/SalesRule/_files/rules_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_category.php
     */
    public function testExecuteFull()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var \Magento\AdvancedSalesRule\Model\Indexer\SalesRule $indexer */
        $indexer = $objectManager->create(\Magento\AdvancedSalesRule\Model\Indexer\SalesRule::class);

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule1 = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Multiple_Categories');

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule2 = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Category');

        /** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter $filter */
        $filter = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter::class
        );

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()
            ->from(['e' => $filter->getMainTable()])
            ->where('rule_id in (?)', [$rule1->getRuleId(), $rule2->getRuleId()]);
        $sql = $filtersSelect->deleteFromSelect('e');
        $connection->query($sql);

        $indexer->executeFull();

        $connection = $filter->getConnection();
        $filtersSelect = $connection->select()
            ->from(['e' => $filter->getMainTable()])
            ->where('rule_id in (?)', [$rule1->getRuleId(), $rule2->getRuleId()]);
        $items = $filtersSelect->query()->fetchAll();
        $this->assertEquals(
            [
                [
                    'rule_id' => $rule1->getRuleId(),
                    'group_id' => '1',
                    'weight' => '0.5',
                    'filter_text' => 'product:category:2',
                    'filter_text_generator_class' =>
                        \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                    'filter_text_generator_arguments' => '[]',
                ],
                [
                    'rule_id' => $rule1->getRuleId(),
                    'group_id' => '1',
                    'weight' => '0.5',
                    'filter_text' => 'product:category:3',
                    'filter_text_generator_class' =>
                        \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                    'filter_text_generator_arguments' => '[]',
                ],
                [
                'rule_id' => $rule2->getRuleId(),
                'group_id' => '1',
                'weight' => '1',
                'filter_text' => 'product:category:66',
                'filter_text_generator_class' =>
                    \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                'filter_text_generator_arguments' => '[]',
                ]
            ],
            $items
        );
    }
}
