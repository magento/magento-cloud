<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Staging\Test\Fixture\Update;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create simple product.
 *
 * Steps:
 * 1. Open product in the admin panel.
 * 2. Schedule new product update.
 * 3. Open product preview from the admin panel.
 * 4. Verify that product price for certain date is correct.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-49851
 */
class PreviewUpdateScheduledChangesTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Page to update a product.
     *
     * @var CatalogProductEdit
     */
    protected $editProductPage;

    /**
     * Injection data.
     *
     * @param CatalogProductEdit $editProductPage
     * @return void
     */
    public function __inject(
        CatalogProductEdit $editProductPage
    ) {
        $this->editProductPage = $editProductPage;
    }

    /**
     * Run preview update permanent scheduled changes.
     *
     * @param CatalogProductSimple $product
     * @param Update $update
     * @param CatalogProductSimple $productUpdate
     * @return array
     */
    public function test(CatalogProductSimple $product, Update $update, CatalogProductSimple $productUpdate)
    {
        // Preconditions
        $product->persist();

        // Test steps
        $this->editProductPage->open(['id' => $product->getId()]);
        $this->editProductPage->getProductScheduleBlock()->clickScheduleNewUpdate();
        $this->editProductPage->getProductScheduleForm()->fill($update);
        $this->editProductPage->getStagingProductForm()->fill($productUpdate);
        $this->editProductPage->getStagingFormPageActions()->save();

        return [
            'updates' => [$update],
            'prices' => [$productUpdate->getPrice()],
            'product' => $product
        ];
    }
}
