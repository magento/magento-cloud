<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardImportExport\Model\Import\Product\Type;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\ImportExport\Model\Import;

class GiftCardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\CatalogImportExport\Model\Import\Product
     */
    protected $model;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->model = $this->objectManager->create(\Magento\CatalogImportExport\Model\Import\Product::class);
    }

    /**
     * @magentoAppArea adminhtml
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testGiftCardImport()
    {
        // Import data from CSV file
        $pathToFile = __DIR__ . '/../../_files/giftcard_product.csv';
        $filesystem = $this->objectManager->create(\Magento\Framework\Filesystem::class);

        $directory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $source = $this->objectManager->create(
            \Magento\ImportExport\Model\Import\Source\Csv::class,
            [
                'file' => $pathToFile,
                'directory' => $directory
            ]
        );
        $errors = $this->model->setSource(
            $source
        )->setParameters(
            [
                'behavior' => \Magento\ImportExport\Model\Import::BEHAVIOR_APPEND,
                'entity' => 'catalog_product'
            ]
        )->validateData();

        $this->assertTrue($errors->getErrorsCount() == 0);
        $this->model->importData();

        $resource = $this->objectManager->get(\Magento\Catalog\Model\ResourceModel\Product::class);

        foreach ($this->getExpectedData() as $expected) {
            $productId = $resource->getIdBySku($expected['sku']);
            $this->assertNotEmpty($productId);
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->objectManager->create(\Magento\Catalog\Model\Product::class);
            $product->load($productId);
            unset($expected['sku']);
            foreach ($expected as $key => $value) {
                if ($key == 'giftcard_amounts') {
                    $amounts = [];
                    foreach ($product->getData($key) as $amount) {
                        $amounts[] = $amount['value'];
                    }
                    $this->assertEquals($amounts, $value);
                    continue;
                }
                $this->assertEquals($product->getData($key), $value);
            }
        }
    }

    /**
     * Return expected data
     *
     * @return array
     */
    protected function getExpectedData()
    {
        return [
            [
                'sku' => 'Virtual Gift Card',
                'giftcard_type' => 0,
                'is_redeemable' => 1,
                'lifetime' => 7,
                'allow_message' => 1,
                'giftcard_amounts' => [
                    5,
                    10,
                    15
                ]
            ],
            [
                'sku' => 'Physical Gift Card',
                'giftcard_type' => 1,
                'is_redeemable' => 1,
                'lifetime' => 14,
                'allow_message' => 1,
                'giftcard_amounts' => [
                    10,
                    15,
                    20
                ]
            ],
            [
                'sku' => 'Combined Gift Card',
                'giftcard_type' => 2,
                'is_redeemable' => 1,
                'lifetime' => 7,
                'allow_message' => 1,
                'giftcard_amounts' => [
                    15,
                    20,
                    25
                ]
            ]
        ];
    }
}
