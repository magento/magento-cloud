<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogPermissions\Model\Indexer;

/**
 * @magentoDbIsolation disabled
 * @magentoAppIsolation enabled
 */
class ProductTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\CatalogPermissions\Model\ResourceModel\Permission\Index
     */
    protected $indexTable;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    public function setUp()
    {
        $this->indexTable = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\CatalogPermissions\Model\ResourceModel\Permission\Index::class
        );
        $this->product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Catalog\Model\Product::class
        );
    }

    /**
     * @test
     *
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     * @magentoDataFixture Magento/CatalogPermissions/_files/permission.php
     * @magentoDataFixture Magento/CatalogPermissions/_files/product.php
     */
    public function testReindexAll()
    {
        $product = $this->getProduct();
        /** @var  $indexer \Magento\Framework\Indexer\IndexerInterface */
        $indexer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Indexer\Model\Indexer::class
        );
        $indexer->load(\Magento\CatalogPermissions\Model\Indexer\Product::INDEXER_ID);
        $indexer->reindexAll();

        $productData = array_merge(['product_id' => $product->getId()], $this->getProductData());
        $this->assertContains($productData, $this->indexTable->getIndexForProduct($product->getId(), 1, 1));

        $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
        $product->save();
        $this->assertEmpty($this->indexTable->getIndexForProduct($product->getId(), 1, 1));
    }

    /**
     * @return array
     */
    protected function getProductData()
    {
        return [
            'grant_catalog_category_view' => '-2',
            'grant_catalog_product_price' => '-2',
            'grant_checkout_items' => '-2'
        ];
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    protected function getProduct()
    {
        return $this->product->load(150);
    }
}
