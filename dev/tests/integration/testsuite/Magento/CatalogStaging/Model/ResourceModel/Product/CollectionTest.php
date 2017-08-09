<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Model\ResourceModel\Product;

/**
 * Product resource collection test.
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Product collection.
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $collection;

    /**
     * Object manager.
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->collection = $this->objectManager->create(
            \Magento\Catalog\Model\ResourceModel\Product\Collection::class
        );
    }

    /**
     * Check product collection AddMediaGalleryData method.
     *
     * @magentoDataFixture Magento/CatalogStaging/_files/simple_product_with_gallery.php
     */
    public function testAddMediaGalleryData()
    {
        /** @var \Magento\Catalog\Api\ProductRepositoryInterface $productRepository */
        $productRepository = $this->objectManager->create(
            \Magento\Catalog\Api\ProductRepositoryInterface::class
        );

        $productWithGallery = $productRepository->get('staging_simple_with_gallery');
        $productWithoutGallery = $productRepository->get('staging_simple_without_gallery');

        $this->collection->clear()
            ->addIdFilter([$productWithGallery->getId(), $productWithoutGallery->getId()])
            ->addMediaGalleryData();

        self::assertEquals(2, $this->collection->count());

        /** @var \Magento\Catalog\Model\Product $itemWithGallery */
        $itemWithGallery = $this->collection->getItemById($productWithGallery->getId());

        /** @var \Magento\Catalog\Model\Product $itemWithoutGallery */
        $itemWithoutGallery = $this->collection->getItemById($productWithoutGallery->getId());

        self::assertNotNull($itemWithGallery);
        self::assertNotNull($itemWithoutGallery);

        self::assertNotEmpty($itemWithGallery->getMediaGalleryEntries());
        self::assertEquals(1, count($itemWithGallery->getMediaGalleryEntries()));
        self::assertStringStartsWith('/m/a/magento_image', $itemWithGallery->getMediaGalleryEntries()[0]->getFile());
        self::assertEmpty($itemWithoutGallery->getMediaGalleryEntries());
    }
}
