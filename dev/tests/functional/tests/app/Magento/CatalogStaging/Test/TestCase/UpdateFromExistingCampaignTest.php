<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Staging\Test\Page\Adminhtml\StagingDashboard;
use Magento\Staging\Test\Page\Adminhtml\StagingUpdateEdit;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Staging\Test\Fixture\Update;

/**
 * Preconditions:
 * 1. Create one simple product with an update campaign.
 * 2. Create one simple product.
 *
 * Steps:
 * 1. Open the product without an update campaign in the admin panel.
 * 2. Assign it to the existing campaign.
 * 3. Go to Staging Dashboard and update that campaign.
 * 4. Open the campaign preview.
 * 5. Perform assertions.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-55074
 */
class UpdateFromExistingCampaignTest extends Injectable
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
     * Staging dashboard page.
     *
     * @var StagingDashboard
     */
    protected $stagingDashboard;

    /**
     * Staging update edit page.
     *
     * @var StagingUpdateEdit
     */
    protected $stagingUpdateEdit;

    /**
     * Perform needed injections.
     *
     * @param CatalogProductEdit $catalogProductEdit
     * @param StagingDashboard $stagingDashboard
     * @param StagingUpdateEdit $stagingUpdateEdit
     * @return void
     */
    public function __inject(
        CatalogProductEdit $catalogProductEdit,
        StagingDashboard $stagingDashboard,
        StagingUpdateEdit $stagingUpdateEdit
    ) {
        $this->catalogProductEdit = $catalogProductEdit;
        $this->stagingDashboard = $stagingDashboard;
        $this->stagingUpdateEdit = $stagingUpdateEdit;
    }

    /**
     * Assign product to previously created campaign test.
     *
     * @param CatalogProductSimple $product
     * @param Update $update
     * @param Update $updateCampaign
     * @param CatalogProductSimple $productUpdate
     * @return array
     */
    public function test(
        CatalogProductSimple $product,
        Update $update,
        Update $updateCampaign,
        CatalogProductSimple $productUpdate
    ) {
        // Preconditions
        $update->persist();
        $product->persist();
        $categoryName = $product->getDataFieldConfig('category_ids')['source']->getCategories()[0]->getName();

        // Test steps
        $this->catalogProductEdit->open(['id' => $product->getId()]);
        $this->catalogProductEdit->getProductScheduleBlock()->clickScheduleNewUpdate();
        $this->catalogProductEdit->getStagingGrid()->assignToExistingCampaign($update->getName());
        $this->catalogProductEdit->getStagingProductForm()->fill($productUpdate);
        $this->catalogProductEdit->getStagingFormPageActions()->save();
        $this->stagingDashboard->open();
        $this->stagingDashboard->getTimelineContent()->openTooltipByUpdateName($update->getName());
        $this->stagingDashboard->getTooltipContent()->editEvent();
        $this->stagingUpdateEdit->getUpdateForm()->fill($updateCampaign);
        $this->stagingUpdateEdit->getFormPageActions()->save();

        return [
            'update' => $updateCampaign,
            'categoryName' => $categoryName,
            'product' => $product,
            'expectedPrice' => $productUpdate->getPrice(),
            'expectedName' => $updateCampaign->getName(),
            'updates' => [$updateCampaign]
        ];
    }
}
