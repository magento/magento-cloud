<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerSegment\Model\Segment\Condition\Product;

use Magento\CustomerSegment\Model\Segment\Condition\Product\Attributes as ProductAttributesCondition;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\CustomerSegment\Model\ResourceModel\Segment as ResourceModel;

/**
 * Test for @see \Magento\CustomerSegment\Model\Segment\Condition\Product\Attributes
 */
class AttributesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ProductAttributesCondition
     */
    protected $productAttributesCondition;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->productAttributesCondition = Bootstrap::getObjectManager()->create(ProductAttributesCondition::class);
    }

    /**
     * @dataProvider getSubfilterSqlDataProvider
     */
    public function testGetSubfilterSql(
        $requireValid,
        $attributeName,
        $attributeValue,
        $productIds,
        $combineProductCondition,
        $expectedResult
    ) {
        $this->productAttributesCondition
            ->setAttribute($attributeName)
            ->setOperator('==')
            ->setValue($attributeValue)
            ->setProductIds($productIds)
            ->setCombineProductCondition($combineProductCondition);
        $website = new \Zend_Db_Expr(':website_id');
        $fieldName = 'item.product_id';
        $result = $this->productAttributesCondition->getSubfilterSql($fieldName, $requireValid, $website);
        $this->assertEquals($expectedResult, $result);
    }

    public function getSubfilterSqlDataProvider()
    {
        /** @var ResourceModel $resource */
        $resource = Bootstrap::getObjectManager()->create(ResourceModel::class);
        $productEntityTable = $resource->getTable('catalog_product_entity');
        $productEntityIntTable = $resource->getTable('catalog_product_entity_int');
        $categoryProductTable = $resource->getTable('catalog_category_product');
        $storeTable = $resource->getTable('store') == 'store'
            ? '`store`'
            : "`{$resource->getTable('store')}` AS `store`";
        return [
            'Non-static attribute' => [
                true,           // $requireValid
                'color',        // $attributeName
                '58',           // $attributeValue
                [],             // $productIds
                false,          // $combineProductCondition
                "item.product_id IN (SELECT `main`.`entity_id` FROM `{$productEntityTable}` AS `main`\n"
                . " INNER JOIN `{$productEntityIntTable}` AS `eav_attribute` ON eav_attribute.row_id=main.row_id\n"
                . " INNER JOIN {$storeTable} ON eav_attribute.store_id=store.store_id"
                . " WHERE (eav_attribute.attribute_id = '93') AND (store.website_id IN (0, :website_id))"
                . " AND (eav_attribute.value = '58') AND (main.created_in <= 1) AND (main.updated_in > 1))"
            ],
            'Category attribute' => [
                false,          // $requireValid
                'category_ids', // $attributeName
                '11',           // $attributeValue
                [],             // $productIds
                false,          // $combineProductCondition
                "item.product_id NOT IN (SELECT `main`.`entity_id` FROM `{$productEntityTable}` AS `main`"
                . " WHERE (main.entity_id IN (SELECT `cat`.`product_id` FROM `{$categoryProductTable}` AS `cat`"
                . " WHERE (cat.category_id = '11'))) AND (main.created_in <= 1) AND (main.updated_in > 1))"
            ],
            'Static attribute; Inverted condition' => [
                true,           // $requireValid
                'sku',          // $attributeName
                'test-sku',     // $attributeValue
                [],             // $productIds
                false,          // $combineProductCondition
                "item.product_id IN (SELECT `main`.`entity_id` FROM `{$productEntityTable}` AS `main` "
                . "WHERE (main.sku = 'test-sku') AND (main.created_in <= 1) AND (main.updated_in > 1))"
            ],
            'Specified product ids; combine product condition' => [
                false,          // $requireValid
                'sku',          // $attributeName
                'test-sku',     // $attributeValue
                [12, 24],       // $productIds
                true,           // $combineProductCondition
                "item.product_id IN (SELECT `main`.`entity_id` FROM `{$productEntityTable}` AS `main` "
                . "WHERE (main.sku = 'test-sku') AND (main.entity_id IN (12, 24)) AND (main.created_in <= 1) "
                . "AND (main.updated_in > 1))"
            ],
        ];
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/quote_with_customer.php
     */
    public function testGetSatisfiedIds()
    {
        $this->productAttributesCondition
            ->setAttribute('name')
            ->setOperator('==')
            ->setValue('Simple Product')
            ->setProductIds([])
            ->setCombineProductCondition(false);
        $websiteId = '1';
        $result = $this->productAttributesCondition->getSatisfiedIds($websiteId);
        $this->assertCount(1, $result);
        /** @var CartRepositoryInterface $cartRepository */
        $cartRepository = Bootstrap::getObjectManager()->create(CartRepositoryInterface::class);
        $fixtureCustomerId = 1;
        $customerCart = $cartRepository->getForCustomer($fixtureCustomerId);
        $this->assertEquals($customerCart->getId(), $result[0]);
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/quote_with_customer.php
     */
    public function testIsSatisfiedBy()
    {
        $this->productAttributesCondition
            ->setAttribute('name')
            ->setOperator('==')
            ->setValue('Simple Product')
            ->setProductIds([])
            ->setCombineProductCondition(false);
        $websiteId = '1';
        $fixtureProductId = 1;
        $matchingParams['quote_item']['product_id'] = $fixtureProductId;
        $this->assertTrue($this->productAttributesCondition->isSatisfiedBy(null, $websiteId, $matchingParams));
        $notMatchingParams['quote_item']['product_id'] = 111111;
        $this->assertFalse($this->productAttributesCondition->isSatisfiedBy(null, $websiteId, $notMatchingParams));
    }
}
