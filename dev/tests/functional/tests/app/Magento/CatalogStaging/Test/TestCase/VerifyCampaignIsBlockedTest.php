<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Staging\Test\Fixture\Update;

/**
 * Preconditions:
 * 1. Create simple product with an update campaign.
 *
 * Steps:
 * 1. Open the product in the admin panel.
 * 2. Add another product update campaign to the created product.
 * 3. Perform assertions.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-55146
 */
class VerifyCampaignIsBlockedTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Catalog product index page.
     *
     * @var CatalogProductEdit
     */
    protected $catalogProductEdit;

    /**
     * Perform needed injections.
     *
     * @param CatalogProductEdit $catalogProductEdit
     * @return void
     */
    public function __inject(
        CatalogProductEdit $catalogProductEdit
    ) {
        $this->catalogProductEdit = $catalogProductEdit;
    }

    /**
     * Verify blocked campaigns test.
     *
     * @param Update $update
     * @param Update $secondUpdate
     * @param CatalogProductSimple $productUpdate
     * @return array
     */
    public function test(
        Update $update,
        Update $secondUpdate,
        CatalogProductSimple $productUpdate
    ) {
        // Preconditions
        $update->persist();
        $product = $update->getProduct();

        // Test steps
        $this->catalogProductEdit->open(['id' => $product->getId()]);
        $this->catalogProductEdit->getProductScheduleBlock()->clickScheduleNewUpdate();
        $this->catalogProductEdit->getProductScheduleForm()->fill($secondUpdate);
        $this->catalogProductEdit->getStagingProductForm()->fill($productUpdate);
        $this->catalogProductEdit->getStagingFormPageActions()->save();

        return [
            'product' => $product,
            'updates' => [$update, $secondUpdate]
        ];
    }
}
