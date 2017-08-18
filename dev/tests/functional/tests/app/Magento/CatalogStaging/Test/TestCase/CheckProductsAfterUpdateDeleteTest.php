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
 * 1. Open second product in the admin panel.
 * 2. Assign second product to the existing campaign.
 * 3. Open first product and remove product update campaign.
 * 4. Open the existing campaign in the admin panel.
 * 5. Perform assertions.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-55086
 */
class CheckProductsAfterUpdateDeleteTest extends Injectable
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
     * Check products in update campaign edit page after having removed the campaign from one the products.
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
        $update->persist();
        $product->persist();
        $campaignProduct = $update->getProduct();

        // Test steps
        $this->catalogProductEdit->open(['id' => $product->getId()]);
        $this->catalogProductEdit->getProductScheduleBlock()->clickScheduleNewUpdate();
        $this->catalogProductEdit->getStagingGrid()->assignToExistingCampaign($update->getName());
        $this->catalogProductEdit->getStagingProductForm()->fill($productUpdate);
        $this->catalogProductEdit->getStagingFormPageActions()->save();

        $this->catalogProductEdit->open(['id' => $campaignProduct->getId()]);
        $this->catalogProductEdit->getProductScheduleBlock()->editUpdate($update->getName());
        $this->catalogProductEdit->getStagingFormPageActions()->remove();
        $this->catalogProductEdit->getUpdateDeleteBlock()->clickDelete();
        $this->catalogProductEdit->getStagingRemovalPageActions()->save();

        return [
            $update,
            'productSkus' => [$product->getSku()]
        ];
    }
}
