<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;

/**
 * Assert that product campaigns created for that product are blocked in grid.
 */
class AssertCampaignsBlocked extends AbstractConstraint
{
    /**
     * Assert that product campaigns created for that product are blocked in grid.
     *
     * @param CatalogProductSimple $product
     * @param CatalogProductEdit $catalogProductEdit
     * @param array $updates
     * @return void
     */
    public function processAssert(
        CatalogProductSimple $product,
        CatalogProductEdit $catalogProductEdit,
        array $updates
    ) {
        $catalogProductEdit->open(['id' => $product->getId()]);
        $catalogProductEdit->getProductScheduleBlock()->clickScheduleNewUpdate();
        $catalogProductEdit->getStagingGrid()->clickAssignToExistingCampaign();
        foreach ($updates as $update) {
            \PHPUnit_Framework_Assert::assertTrue(
                $catalogProductEdit->getStagingGrid()->campaignIsBlocked($update->getName()),
                $update->getName() . ' is expected to be blocked.'
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Campaigns are blocked.';
    }
}
