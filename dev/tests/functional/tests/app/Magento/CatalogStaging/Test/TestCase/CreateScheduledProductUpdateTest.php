<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
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
 * 1. Create product update campaign with different time ranges.
 * 2. Verify that correct error messages are displayed in case campaign can not be created.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-49851, MAGETWO-49852, MAGETWO-49052, MAGETWO-55015, MAGETWO-55016
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
