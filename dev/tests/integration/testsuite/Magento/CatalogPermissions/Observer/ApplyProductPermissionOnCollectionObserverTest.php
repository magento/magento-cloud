<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogPermissions\Observer;

use Magento\CatalogPermissions\Model\Indexer\Category;
use Magento\CatalogPermissions\Model\Indexer\Product;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection as FulltextCollection;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\CollectionFactory as FulltextCollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Indexer\IndexerInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Test for \Magento\CatalogPermissions\Observer\ApplyProductPermissionOnCollectionObserverTest class.
 *
 * @magentoDbIsolation disabled
 */
class ApplyProductPermissionOnCollectionObserverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CollectionFactory
     */
    private $fulltextCollectionFactory;

    /**
     * @var IndexerInterface
     */
    private $categoryIndexer;

    /**
     * @var IndexerInterface
     */
    private $productIndexer;

    /**
     * @var Session
     */
    private $session;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $objectManager = Bootstrap::getObjectManager();

        $this->fulltextCollectionFactory = $objectManager->create(FulltextCollectionFactory::class);

        /** @var IndexerRegistry $indexerRegistry */
        $indexerRegistry = $objectManager->create(IndexerRegistry::class);
        $this->categoryIndexer = $indexerRegistry->get(Category::INDEXER_ID);
        $this->productIndexer = $indexerRegistry->get(Product::INDEXER_ID);

        $this->session = $objectManager->get(Session::class);
    }

    /**
     * Test search collection size.
     *
     * @param int $customerGroupId
     * @param string $query
     * @param int $expectedSize
     * @dataProvider searchCollectionSizeDataProvider
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled true
     * @magentoDataFixture Magento/CatalogPermissions/_files/category_products_deny.php
     * @magentoAppArea frontend
     */
    public function testSearchCollectionSize($customerGroupId, $query, $expectedSize)
    {
        $this->categoryIndexer->reindexAll();
        $this->productIndexer->reindexAll();
        $this->session->setCustomerGroupId($customerGroupId);

        /** @var FulltextCollection $collection */
        $collection = $this->fulltextCollectionFactory->create([
            'searchRequestName' => 'quick_search_container'
        ]);

        $collection->addSearchFilter($query);
        $collection->setVisibility([3, 4]);

        $this->assertEquals($expectedSize, count($collection->getItems()));
        $this->assertEquals($expectedSize, $collection->getSize());
    }

    /**
     * Data provider for `testSearchCollectionSize` method.
     *
     * @return array
     */
    public function searchCollectionSizeDataProvider()
    {
        return [
            [1, 'simple_deny_122', 0],
            [1, 'simple_allow_122', 1],
            [1, 'simple_', 1],
            [0, 'simple_', 0],
        ];
    }
}
