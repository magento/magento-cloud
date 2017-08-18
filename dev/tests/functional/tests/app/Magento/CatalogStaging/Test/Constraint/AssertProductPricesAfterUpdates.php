<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Staging\Test\Page\Adminhtml\StagingDashboard;
use Magento\Staging\Test\Page\Adminhtml\StagingUpdatePreview;

/**
 * Assert that product info is correct in frontend according to specified update time.
 */
class AssertProductPricesAfterUpdates extends AbstractConstraint
{
    /**
     * Assert that product info is correct in frontend according to specified update time.
     *
     * @param StagingDashboard $stagingDashboardPage
     * @param StagingUpdatePreview $stagingUpdatePreview
     * @param array $updates
     * @param array $prices
     * @param array $products
     * @return void
     */
    public function processAssert(
        StagingDashboard $stagingDashboardPage,
        StagingUpdatePreview $stagingUpdatePreview,
        array $updates,
        array $prices,
        array $products
    ) {
        $stageStep = 0;
        foreach ($updates as $update) {
            $productKey = 0;
            ++$stageStep;
            foreach ($products as $product) {
                if (isset($prices[$productKey][$stageStep])) {
                    $stagingDashboardPage->open();
                    $stagingDashboardPage->getTimelineContent()->openTooltipByUpdateName($update->getName());
                    $stagingDashboardPage->getTooltipContent()->previewEvent();
                    $categoryName = $product->getDataFieldConfig('category_ids')['source']
                        ->getCategories()[0]
                        ->getName();
                    $stagingUpdatePreview->getNavigationBlock()->openCategory($categoryName);
                    $stagingUpdatePreview->getProductsListBlock()->openProduct($product->getName());
                    \PHPUnit_Framework_Assert::assertEquals(
                        $stagingUpdatePreview->getProductInfoBlock()->getPrice(),
                        $prices[$productKey][$stageStep],
                        'Expected in preview price of ' . $product->getName()
                        .' with ID ' . $productKey . ' is not correct at update # '
                        . $stageStep . '.'
                    );
                }
                $productKey++;
            }
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Expected in preview product price is correct.';
    }
}
