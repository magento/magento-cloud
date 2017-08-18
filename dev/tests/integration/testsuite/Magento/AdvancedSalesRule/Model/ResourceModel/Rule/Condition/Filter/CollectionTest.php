<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 * @magentoDataFixture Magento/SalesRule/_files/rules_category.php
 */
class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * test collection
     */
    public function testCollection()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule = $objectManager->get(\Magento\Framework\Registry::class)
            ->registry('_fixture/Magento_SalesRule_Category');

        /** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter\Collection $filterCollection */
         $filterCollection = $objectManager
            ->create(\Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter\Collection::class);

        $filterCollection = $filterCollection->addFilter('rule_id', $rule->getRuleId())->loadWithFilter();

        //based on rules_categories fixture
        $this->assertEquals(
            [
                'rule_id' => $rule->getRuleId(),
                'group_id' => '1',
                'weight' => '1',
                'filter_text' => 'product:category:66',
                'filter_text_generator_class' =>
                    \Magento\AdvancedSalesRule\Model\Rule\Condition\FilterTextGenerator\Product\Category::class,
                'filter_text_generator_arguments' => '[]'
            ],
            current($filterCollection->getItems())->getData()
        );
    }
}
