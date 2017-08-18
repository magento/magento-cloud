<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogStaging\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductEdit;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;

/**
 * Assert that product has no update campaigns.
 */
class AssertProductHasNoUpdates extends AbstractConstraint
{
    /**
     * Open product edit page and assert that product has no specified update campaigns.
     *
     * @param CatalogProductEdit $catalogProductEdit
     * @param CatalogProductSimple $product
     * @param array $updates
     * @return void
     */
    public function processAssert(
        CatalogProductEdit $catalogProductEdit,
        CatalogProductSimple $product,
        array $updates
    ) {
        $catalogProductEdit->open(['id' => $product->getId()]);

        foreach ($updates as $update) {
            \PHPUnit_Framework_Assert::assertFalse(
                $catalogProductEdit->getProductScheduleBlock()->updateCampaignExists($update->getName()),
                $update->getName() . ' is supposed to be removed.'
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
        return 'Product has no update campaigns.';
    }
}
