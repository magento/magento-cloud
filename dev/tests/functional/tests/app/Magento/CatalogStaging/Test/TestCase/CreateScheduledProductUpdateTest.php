<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\TestCase\Injectable;
use Magento\Staging\Test\Fixture\Update;

/**
 * Preconditions:
 * 1. Create simple product.
 *
 * Steps:
 * 1. Login to backend.
 * 2. Navigate to PRODUCTS -> Catalog.
 * 3. Select a product in the grid.
 * 4. Start to create schedule update.
 * 4. Fill in data according to dataset.
 * 5. Click "Save".
 * 6. Perform asserts.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-69538
 */
class CreateScheduledProductUpdateTest extends Injectable
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
     * Create scheduled product update test.
     *
     * @param CatalogProductSimple $product
     * @param Update $update
     * @return array
     */
    public function test(CatalogProductSimple $product, Update $update)
    {
        // Preconditions
        $product->persist();

        // Test steps
        $this->editProductPage->open(['id' => $product->getId()]);
        $this->editProductPage->getProductScheduleBlock()->clickScheduleNewUpdate();
        $this->editProductPage->getProductScheduleForm()->fill($update);
        $this->editProductPage->getStagingFormPageActions()->save();

        return [
            'updates' => [$update]
        ];
    }
}
