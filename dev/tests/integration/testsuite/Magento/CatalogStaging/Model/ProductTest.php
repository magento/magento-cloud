<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogStaging\Model;

/**
 * Product test.
 */
class ProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Object manager.
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Product repository.
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Product.
     *
     * @var \Magento\Catalog\Model\Product
     */
    protected $_model;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->productRepository = $this->objectManager->create(
            \Magento\Catalog\Api\ProductRepositoryInterface::class
        );
        $this->_model = $this->objectManager->create(\Magento\Catalog\Model\Product::class);
    }

    /**
     * Test product duplication with media gallery.
     *
     * @magentoAppArea adminhtml
     * @magentoDataFixture Magento/CatalogStaging/_files/staging_products_with_media.php
     */
    public function testDuplicateMediaGallery()
    {
        $this->_model = $this->productRepository->get('simple-staging-with-gallery-2');

        /** @var \Magento\Catalog\Model\Product\Copier $copier */
        $copier = $this->objectManager->get(\Magento\Catalog\Model\Product\Copier::class);
        $duplicate = $copier->copy($this->_model);

        $duplicate = $this->productRepository->get(
            $duplicate->getSku(),
            false,
            \Magento\Store\Model\Store::DEFAULT_STORE_ID,
            true
        );

        try {
            $this->assertNotEmpty($duplicate->getId());
            $this->assertNotEquals($duplicate->getId(), $this->_model->getId());
            $this->assertNotEquals($duplicate->getSku(), $this->_model->getSku());
            $this->assertEquals(
                \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED,
                $duplicate->getStatus()
            );
            $this->assertEquals(\Magento\Store\Model\Store::DEFAULT_STORE_ID, $duplicate->getStoreId());
            $galleryImages = $duplicate->getMediaGalleryImages()->getItems();

            self::assertEquals(2, count($galleryImages), 'Wrong gallery image count.');
            $errors = [];

            $images = ['/i/m/image3', '/i/m/image4'];

            foreach ($images as $image) {
                $found = false;

                foreach ($galleryImages as $galleryEntry) {
                    if (strpos($galleryEntry->getFile(), $image) !== false) {
                        $found = true;
                        break;
                    }
                }

                if ($found === false) {
                    $errors[] = sprintf(
                        "Image '%s' is not present in duplicated product gallery.",
                        $image
                    );
                }
            }

            self::assertCount(0, $errors, implode(PHP_EOL, $errors));

            $this->undo($duplicate);
        } catch (\Exception $e) {
            $this->undo($duplicate);
            throw $e;
        }
    }

    /**
     * Delete model.
     *
     * @param \Magento\Framework\Model\AbstractModel $duplicate
     */
    private function undo($duplicate)
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Store\Model\StoreManagerInterface::class
        )->getStore()->setId(
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );
        $duplicate->delete();
    }
}
