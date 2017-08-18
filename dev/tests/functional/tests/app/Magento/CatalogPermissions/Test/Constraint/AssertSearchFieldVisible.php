<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that search field is present in header.
 */
class AssertSearchFieldVisible extends AbstractConstraint
{
    /**
     * Assert that search field is visible in header.
     *
     * @param CmsIndex $cmsIndex
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex
    ) {
        $cmsIndex->open();
        \PHPUnit_Framework_Assert::assertTrue(
            $cmsIndex->getSearchBlock()->isVisible(),
            'Search field is not visible on the storefront.'
        );
    }

    /**
     * Returns string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Search field is visible on the storefront.';
    }
}
