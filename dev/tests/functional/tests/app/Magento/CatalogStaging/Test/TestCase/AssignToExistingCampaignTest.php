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
 * 2. Create simple product.
 *
 * Steps:
 * 1. Create an update campaign for the first product.
 * 2. Assign second product to that campaign.
 * 3. Open second product in the admin panel.
 * 4. Perform assertions.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-49053, MAGETWO-55004
 */
class AssignToExistingCampaignTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Catalog product edit page.
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
     * Assign product to previously created campaign test.
     *
     * @param CatalogProductSimple $product
     * @param CatalogProductSimple $productUpdate
     * @param Update $update
     * @return array
     */
    public function test(
        CatalogProductSimple $product,
        CatalogProductSimple $productUpdate,
        Update $update
    ) {
        // Preconditions
        $product->persist();
        $update->persist();

        // Test steps
        $this->catalogProductEdit->open(['id' => $product->getId()]);
        $this->catalogProductEdit->getProductScheduleBlock()->clickScheduleNewUpdate();
        $this->catalogProductEdit->getStagingGrid()->assignToExistingCampaign($update->getName());
        $this->catalogProductEdit->getStagingProductForm()->fill($productUpdate);
        $this->catalogProductEdit->getStagingFormPageActions()->save();

        return [
            $product,
            'updates' => [$update],
            'productSkus' => [$product->getSku(), $update->getProduct()->getSku()]
        ];
    }
}
