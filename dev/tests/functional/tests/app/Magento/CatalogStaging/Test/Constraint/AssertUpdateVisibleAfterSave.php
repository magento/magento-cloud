<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;

/**
 * Assert that product update is still visible after save.
 */
class AssertUpdateVisibleAfterSave extends AbstractConstraint
{
    /**
     * Assert that product update is visible after save.
     *
     * @param array $updates
     * @param CatalogProductEdit $catalogProductEdit
     * @param CatalogProductSimple $product
     * @return void
     */
    public function processAssert(
        array $updates,
        CatalogProductEdit $catalogProductEdit,
        CatalogProductSimple $product
    ) {
        $catalogProductEdit->open(['id' => $product->getId()]);

        foreach ($updates as $update) {
            \PHPUnit_Framework_Assert::assertTrue(
                $catalogProductEdit->getProductScheduleBlock()->updateCampaignExists($update->getName()),
                $update->getName() . ' should be visible.'
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
        return 'Product displays the update after it has been saved.';
    }
}
