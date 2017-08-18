<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Staging\Test\Page\Adminhtml\StagingDashboard;
use Magento\Staging\Test\Page\Adminhtml\StagingUpdateEdit;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Staging\Test\Fixture\Update;

/**
 * Assert that products are visible in products grid on update campaign edit page.
 */
class AssertProductsCorrectInStagingGrid extends AbstractConstraint
{
    /**
     * Assert that products are visible in products grid on update campaign edit page.
     *
     * @param StagingDashboard $stagingDashboardPage
     * @param StagingUpdateEdit $editPage
     * @param CatalogProductEdit $productEditPage
     * @param array $updates
     * @param array $products
     * @return void
     */
    public function processAssert(
        StagingDashboard $stagingDashboardPage,
        StagingUpdateEdit $editPage,
        CatalogProductEdit $productEditPage,
        array $updates,
        array $products
    ) {
        $this->checkScheduleIsCorrect($updates[2], $stagingDashboardPage);
        $stagingDashboardPage->getTooltipContent()->editEvent();
        $editPage->getUpdateForm()->openSection('products');
        $this->productsAreCorrect($products, $editPage);
        $editPage->getUpdateForm()->getSection('products')->getProductsGrid()->clickEditProductLink($products[0]);
        $productEditPage->getStagingGrid()->assignToExistingCampaign($updates[3]->getName());
        $productEditPage->getStagingFormPageActions()->save();
        $this->checkScheduleIsCorrect($updates[1], $stagingDashboardPage);
        $stagingDashboardPage->getTooltipContent()->editEvent();
        $editPage->getUpdateForm()->openSection('products');
        $productsForFirstStage = $products;
        unset($productsForFirstStage[1]);
        $this->productsAreCorrect($productsForFirstStage, $editPage);
    }

    /**
     * Check schedule is correct.
     *
     * @param Update $update
     * @param StagingDashboard $stagingDashboardPage
     * @return void
     */
    private function checkScheduleIsCorrect(Update $update, StagingDashboard $stagingDashboardPage)
    {
        $stagingDashboardPage->open();
        $stagingDashboardPage->getTimelineContent()->openTooltipByUpdateName($update->getName());

        $result = true;
        $text = $stagingDashboardPage->getTooltipContent()->getContents();

        foreach ($update as $item) {
            $result = strpos($text, $item);
            if (!$result) {
                break;
            }
        }

        \PHPUnit_Framework_Assert::assertTrue(
            $result,
            'Hover content is not correct.'
        );
    }

    /**
     * Check that correct products are visible.
     *
     * @param array $products
     * @param StagingUpdateEdit $editPage
     * @return void
     */
    private function productsAreCorrect(array $products, StagingUpdateEdit $editPage)
    {
        $result = false;
        $productName = '';
        foreach ($products as $product) {
            $result = $editPage->getUpdateForm()->getSection('products')->getProductsGrid()->isProductVisible($product);

            if (!$result) {
                $productName = $product->getName();
                break;
            }
        }

        \PHPUnit_Framework_Assert::assertTrue(
            $result,
            'Product ' . $productName . ' is not visible in products grid on update campaign edit page.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Staging grid is correct.';
    }
}
