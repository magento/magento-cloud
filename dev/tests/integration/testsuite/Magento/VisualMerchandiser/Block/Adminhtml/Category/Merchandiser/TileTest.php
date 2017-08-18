<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\VisualMerchandiser\Block\Adminhtml\Category\Merchandiser;

/**
 * @magentoAppArea adminhtml
 */
class TileTest extends \PHPUnit\Framework\TestCase
{
    const CATEGORY_ID = 333;
    const FIRST_WEBSITE_STORE = 1;
    const SECOND_WEBSITE_FIRST_STORE = 2;
    const SECOND_WEBSITE_SECOND_STORE = 3;

    /**
     * @var \Magento\VisualMerchandiser\Block\Adminhtml\Category\Merchandiser\Tile
     */
    private $tile;

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $layout = $this->objectManager->get(\Magento\Framework\View\LayoutInterface::class);

        /** @var \Magento\VisualMerchandiser\Block\Adminhtml\Category\Merchandiser\Tile $tile */
        $this->tile = $layout->createBlock(
            \Magento\VisualMerchandiser\Block\Adminhtml\Category\Merchandiser\Tile::class
        );
        $this->tile->setPositionCacheKey('cache_key');
    }

    /**
     * @magentoDataFixture Magento/Store/_files/second_website_with_two_stores.php
     * @magentoDataFixture Magento/Catalog/_files/category.php
     * @magentoDataFixture Magento/VisualMerchandiser/Block/Adminhtml/Category/Merchandiser/_files/products_with_websites_and_stores.php
     */
    public function testGetPreparedCollection()
    {
        // The first website
        $this->tile->getRequest()->setParams(
            [
                'id' => self::CATEGORY_ID,
                'store' => $this->getStoreId(self::FIRST_WEBSITE_STORE)
            ]
        );
        $collection = $this->tile->getPreparedCollection();
        $products = $collection->getItems();
        $this->assertProducts(
            [
                'Simple Product',
                'Simple Product on both website',
            ],
            $products
        );

        // The second website the first store view
        $this->tile->getRequest()->setParams(
            [
                'id' => self::CATEGORY_ID,
                'store' => $this->getStoreId(self::SECOND_WEBSITE_FIRST_STORE)
            ]
        );
        $collection = $this->tile->getPreparedCollection();
        $products = $collection->getItems();
        $this->assertProducts(
            [
                'Simple Product on second website',
                'Simple Product on both website',
            ],
            $products
        );

        // The second website the second store view
        $this->tile->getRequest()->setParams(
            [
                'id' => self::CATEGORY_ID,
                'store' => $this->getStoreId(self::SECOND_WEBSITE_SECOND_STORE)
            ]
        );
        $collection = $this->tile->getPreparedCollection();
        $products = $collection->getItems();
        $this->assertProducts(
            [
                'Simple Product on second website',
                'Simple Product on both website',
            ],
            $products
        );

        // All websites
        $this->tile->getRequest()->setParams(
            [
                'id' => self::CATEGORY_ID,
                'store' => $this->getStoreId('no store')
            ]
        );
        $collection = $this->tile->getPreparedCollection();
        $products = $collection->getItems();
        $this->assertProducts(
            [
                'Simple Product on second website',
                'Simple Product on both website',
                'Simple Product',
                'Simple Product without website',
            ],
            $products
        );
    }

    /**
     * assert products
     *
     * @param $expectProductNames
     * @param $products
     */
    private function assertProducts($expectProductNames, $products)
    {
        $productNames = [];
        /** @var  $product */
        foreach ($products as $product) {
            $this->assertInstanceOf(\Magento\Catalog\Model\Product::class, $product);
            /** @var \Magento\Catalog\Model\Product $product */
            $productNames[] = $product->getName();
        }

        $this->assertEmpty(array_diff($expectProductNames, $productNames));
        $this->assertEmpty(array_diff($productNames, $expectProductNames));
    }

    /**
     * Get store id
     *
     * @param string $key
     * @return int|null
     */
    private function getStoreId($key)
    {
        switch ($key) {
            case self::FIRST_WEBSITE_STORE:
                /** @var \Magento\Store\Model\Website $website */
                $website = $this->objectManager->create(\Magento\Store\Model\Website::class);
                $storeIds = $website->load('1', 'website_id')->getStoreIds();
                return array_shift($storeIds);
            case self::SECOND_WEBSITE_FIRST_STORE:
                $store = $this->objectManager->create(\Magento\Store\Model\Store::class);
                return $store->load('fixture_second_store', 'code')->getId();
            case self::SECOND_WEBSITE_SECOND_STORE:
                $store = $this->objectManager->create(\Magento\Store\Model\Store::class);
                return $store->load('fixture_third_store', 'code')->getId();
            default:
                return null;
        }
    }
}
