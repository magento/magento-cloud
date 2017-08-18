<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\TestCase;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\TestCase\Injectable;
use Magento\Staging\Test\Fixture\Update;

/**
 * Preconditions:
 * 1. Create simple product with an update campaign.
 *
 * Steps:
 * 1. Create another product update with the same date range.
 * 2. Verify that correct error message is displayed.
 *
 * @group CatalogStaging
 * @ZephyrId MAGETWO-55018
 */
class SameUpdateDateRangesTest extends Injectable
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
     * @param Update $update
     * @param Update $sameUpdateCampaign
     * @return array
     */
    public function test(Update $update, Update $sameUpdateCampaign)
    {
        // Preconditions
        $update->persist();
        $product = $update->getProduct();

        // Test steps
        $this->editProductPage->open(['id' => $product->getId()]);
        $this->editProductPage->getProductScheduleBlock()->clickScheduleNewUpdate();
        $this->editProductPage->getProductScheduleForm()->fill($sameUpdateCampaign);
        $this->editProductPage->getStagingFormPageActions()->save();

        return [
            'updates' => [$update]
        ];
    }
}
