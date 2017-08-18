<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\TestCase;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductIndex;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductNew;
use Magento\GiftCard\Test\Fixture\GiftCardProduct;
use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Test Creation for UpdateGiftCardProductEntity
 *
 * Test Flow:
 *
 * Preconditions:
 * 1. Create Gift Card Product.
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Open Products > Catalog.
 * 3. Open created Gift Card Product for edit.
 * 4. Fill in data according to attached data set.
 * 5. Save Product.
 * 6. Perform appropriate assertions.
 *
 * @group Gift_Card
 * @ZephyrId MAGETWO-28819
 */
class UpdateGiftCardProductEntityTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    /* end tags */

    /**
     * Catalog Product Index page
     *
     * @var CatalogProductIndex
     */
    protected $productIndex;

    /**
     * Catalog Product New page
     *
     * @var CatalogProductNew
     */
    protected $productNew;

    /**
     * Fixture Factory.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Inject pages
     *
     * @param CatalogProductIndex $productIndex
     * @param CatalogProductNew $productNew
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __inject(
        CatalogProductIndex $productIndex,
        CatalogProductNew $productNew,
        FixtureFactory $fixtureFactory
    ) {
        $this->productIndex = $productIndex;
        $this->productNew = $productNew;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Update Gift Card product entity
     *
     * @param GiftCardProduct $product
     * @param GiftCardProduct $productOriginal
     * @param string $storeDataset [optional]
     * @param int $storesCount [optional]
     * @param int|null $storeIndexToUpdate [optional]
     * @return array
     */
    public function test(
        GiftCardProduct $product,
        GiftCardProduct $productOriginal,
        $storeDataset = '',
        $storesCount = 0,
        $storeIndexToUpdate = null
    ) {
        $productOriginal->persist();
        $stores = [];
        $productNames = [];
        if ($storeDataset) {
            for ($i = 0; $i < $storesCount; $i++) {
                $stores[$i] = $this->fixtureFactory->createByCode('store', ['dataset' => $storeDataset]);
                $stores[$i]->persist();
                $productNames[$stores[$i]->getStoreId()] = $productOriginal->getName();
            }
            if ($storeIndexToUpdate !== null) {
                $productNames[$stores[$storeIndexToUpdate]->getStoreId()] = $product->getName();
            }
        }

        $this->productIndex->open();
        $this->productIndex->getProductGrid()->searchAndOpen(['sku' => $productOriginal->getSku()]);
        if ($storeDataset && $storeIndexToUpdate !== null) {
            $this->productNew->getFormPageActions()->changeStoreViewScope($stores[$storeIndexToUpdate]);
        }
        $this->productNew->getProductForm()->fill($product);
        $this->productNew->getFormPageActions()->save();

        return [
            'initialProduct' => $productOriginal,
            'stores' => $stores,
            'productNames' => $productNames,
        ];
    }
}
