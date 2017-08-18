<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Staging\Test\Page\Adminhtml\StagingDashboard;
use Magento\Staging\Test\Page\Adminhtml\StagingUpdatePreview;
use Magento\Staging\Test\Fixture\Update;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;

/**
 * Verify that product price is correct in campaign preview in the storefront.
 */
class AssertAssignedExistingCampaignPreviewCorrect extends AbstractConstraint
{
    /**
     * Assert that product price in update campaign preview is correct.
     *
     * @param StagingDashboard $stagingDashboardPage
     * @param StagingUpdatePreview $stagingUpdatePreview
     * @param CatalogProductSimple $product
     * @param Update $update
     * @param string $categoryName
     * @param float $expectedPrice
     * @return void
     */
    public function processAssert(
        StagingDashboard $stagingDashboardPage,
        StagingUpdatePreview $stagingUpdatePreview,
        CatalogProductSimple $product,
        Update $update,
        $categoryName,
        $expectedPrice
    ) {
        $stagingDashboardPage->open();
        $stagingDashboardPage->getTimelineContent()->openTooltipByUpdateName($update->getName());
        $stagingDashboardPage->getTooltipContent()->previewEvent();
        $stagingUpdatePreview->getNavigationBlock()->openCategory($categoryName);
        $stagingUpdatePreview->getProductsListBlock()->openProduct($product->getName());
        \PHPUnit_Framework_Assert::assertEquals(
            $stagingUpdatePreview->getProductInfoBlock()->getPrice(),
            $expectedPrice,
            'Expected in preview price is not correct.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Expected in preview price is correct.';
    }
}
