<?php
/**
 * @category    Magento
 * @package     Magento_TargetRule
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\TargetRule\Model\Indexer\TargetRule\Rule\Product\Action;

class RowTest extends \Magento\TestFramework\Indexer\TestCase
{
    /**
     * @var \Magento\TargetRule\Model\Indexer\TargetRule\Rule\Product\Processor
     */
    protected $_processor;

    /**
     * @var \Magento\TargetRule\Model\RuleFactory
     */
    protected $_ruleFactory;

    protected function setUp()
    {
        $this->_processor = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\TargetRule\Model\Indexer\TargetRule\Rule\Product\Processor::class
        );
        $this->_ruleFactory = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\TargetRule\Model\RuleFactory::class
        );
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/products.php
     */
    public function testReindexRow()
    {
        $this->_processor->getIndexer()->setScheduled(false);
        $this->assertFalse($this->_processor->getIndexer()->isScheduled());

        $data = [
            'name' => 'Target Rule',
            'is_active' => '1',
            'apply_to' => 1,
            'use_customer_segment' => '0',
            'customer_segment_ids' => ['0' => ''],
        ];
        $rule = $this->_ruleFactory->create();
        $rule->loadPost($data);
        $rule->save();

        $this->assertEquals(2, count($rule->getMatchingProductIds()));
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     */
    public function testReindexRowByCategories()
    {
        /**
         * @var \Magento\Catalog\Model\ResourceModel\Product $productRepository
         */
        $productRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(\Magento\Catalog\Model\ResourceModel\Product::class);
        $this->_processor->getIndexer()->setScheduled(false);
        $this->assertFalse($this->_processor->getIndexer()->isScheduled());

        $data = [
            'name' => 'related',
            'is_active' => '1',
            'apply_to' => 1,
            'use_customer_segment' => '0',
            'customer_segment_ids' => ['0' => ''],
            'conditions' => [
                '1' => [
                    'type' => \Magento\TargetRule\Model\Rule\Condition\Combine::class,
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => '',
                ],
                '1--1' => [
                    'type' => \Magento\TargetRule\Model\Rule\Condition\Product\Attributes::class,
                    'attribute' => 'category_ids',
                    'operator' => '()',
                    'value' => '11',
                ],
            ],
        ];
        $rule = $this->_ruleFactory->create();
        $rule->loadPost($data);
        $rule->save();

        $testSelect = $rule->getResource()->getConnection()->select()->from(
            $rule->getResource()->getTable('magento_targetrule_product'),
            'product_id'
        )->where(
            'rule_id = ?', $rule->getId()
        );

        $productIds = [$productRepository->getIdBySku('simple-3'), $productRepository->getIdBySku('simple-4')];
        $this->assertEquals($productIds, $rule->getResource()->getConnection()->fetchCol($testSelect));

        $data = [
            'name' => 'related',
            'is_active' => '1',
            'apply_to' => 1,
            'use_customer_segment' => '0',
            'customer_segment_ids' => ['0' => ''],
            'conditions' => [
                '1' => [
                    'type' => \Magento\TargetRule\Model\Rule\Condition\Combine::class,
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => '',
                ],
                '1--1' => [
                    'type' => \Magento\TargetRule\Model\Rule\Condition\Product\Attributes::class,
                    'attribute' => 'category_ids',
                    'operator' => '==',
                    'value' => '5',
                ],
            ],
        ];
        $rule = $this->_ruleFactory->create();
        $rule->loadPost($data);
        $rule->save();

        $testSelect = $rule->getResource()->getConnection()->select()->from(
            $rule->getResource()->getTable('magento_targetrule_product'),
            'product_id'
        )->where(
            'rule_id = ?', $rule->getId()
        );

        $productId = $productRepository->getIdBySku('12345');
        $this->assertEquals([$productId], $rule->getResource()->getConnection()->fetchCol($testSelect));
    }
}
