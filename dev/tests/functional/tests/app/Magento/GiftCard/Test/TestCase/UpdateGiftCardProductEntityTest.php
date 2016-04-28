<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\TestCase;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductIndex;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductNew;
use Magento\GiftCard\Test\Fixture\GiftCardProduct;
use Magento\Mtf\TestCase\Injectable;

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
 * @group Gift_Card_(MX)
 * @ZephyrId MAGETWO-28819
 */
class UpdateGiftCardProductEntityTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    const DOMAIN = 'MX';
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
     * Inject pages
     *
     * @param CatalogProductIndex $productIndex
     * @param CatalogProductNew $productNew
     * @return void
     */
    public function __inject(CatalogProductIndex $productIndex, CatalogProductNew $productNew)
    {
        $this->productIndex = $productIndex;
        $this->productNew = $productNew;
    }

    /**
     * Update Gift Card product entity
     *
     * @param GiftCardProduct $product
     * @param GiftCardProduct $productOriginal
     * @return void
     */
    public function test(GiftCardProduct $product, GiftCardProduct $productOriginal)
    {
        $productOriginal->persist();
        $this->productIndex->open();
        $this->productIndex->getProductGrid()->searchAndOpen(['sku' => $productOriginal->getSku()]);
        $this->productNew->getProductForm()->fill($product);
        $this->productNew->getFormPageActions()->save();
    }
}
