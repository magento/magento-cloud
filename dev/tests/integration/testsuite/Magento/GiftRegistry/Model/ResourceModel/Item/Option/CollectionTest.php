<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftRegistry\Model\ResourceModel\Item\Option;

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    public function testAddProductFilter()
    {
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\GiftRegistry\Model\ResourceModel\Item\Option\Collection::class
        );
        $select = $collection->getSelect();
        $this->assertSame([], $select->getPart(\Magento\Framework\DB\Select::WHERE));

        $product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Catalog\Model\Product::class
        );
        $product->setId(4);
        $collection->addProductFilter(1)->addProductFilter([2, 3])->addProductFilter($product);
        $this->assertStringMatchesFormat(
            '%AWHERE%S(%Aproduct_id%A = %S1%S)%SAND%S(%Aproduct_id%A IN(%S2%S,%S3%S))%SAND%S(%Aproduct_id%A = %S4%S)%A',
            (string)$select
        );
    }

    public function testAddProductFilterZero()
    {
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\GiftRegistry\Model\ResourceModel\Item\Option\Collection::class
        );
        $collection->addProductFilter(0);
        $this->assertSame([], $collection->getSelect()->getPart(\Magento\Framework\DB\Select::WHERE));
        foreach ($collection as $item) {
            $this->fail("Unexpected item in collection: {$item->getId()}");
        }
    }
}
