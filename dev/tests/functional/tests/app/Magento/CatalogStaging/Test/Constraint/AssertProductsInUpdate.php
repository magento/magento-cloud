<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Staging\Test\Page\Adminhtml\StagingUpdateEdit;
use Magento\Staging\Test\Fixture\Update;
use Magento\Staging\Test\Page\Adminhtml\StagingDashboard;

/**
 * Assert that correct products are assigned to a certain product update campaign.
 */
class AssertProductsInUpdate extends AbstractConstraint
{
    /**
     * Assert that correct products are assigned to a certain product update campaign.
     *
     * @param StagingDashboard $stagingDashboardPage
     * @param StagingUpdateEdit $stagingUpdateEdit
     * @param Update $update
     * @param array $productSkus
     * @return void
     */
    public function processAssert(
        StagingDashboard $stagingDashboardPage,
        StagingUpdateEdit $stagingUpdateEdit,
        Update $update,
        array $productSkus
    ) {
        $stagingDashboardPage->open();
        $stagingDashboardPage->getTimelineContent()->openTooltipByUpdateName($update->getName());
        $stagingDashboardPage->getTooltipContent()->editEvent();
        $stagingUpdateEdit->getUpdateForm()->openSection('products');
        $actualProducts = $stagingUpdateEdit
            ->getUpdateForm()
            ->getSection('products')
            ->getProductsGrid()
            ->getColumnValues('SKU');
        sort($productSkus);
        sort($actualProducts);
        \PHPUnit_Framework_Assert::assertEquals(
            $productSkus,
            $actualProducts,
            'Products in update campaign ' . $update->getName() . 'are not correct.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Products assigned to the update campaign are correct.';
    }
}
