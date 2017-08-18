<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Test\Constraint;

use Magento\Catalog\Test\Fixture\Category;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that category is absent in header.
 */
class AssertCategoryPresent extends AbstractConstraint
{
    /**
     * Asserts that category is present or absent in topmenu.
     *
     * @param CmsIndex $cmsIndex
     * @param array $categories
     * @param array $categoryPermissions
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        array $categories,
        array $categoryPermissions
    ) {
        $cmsIndex->open();
        /** @var Category $category */
        foreach ($categories as $key => $category) {
            $categoryName = $category->getName();
            $category->getCategoryPermissions();
            $categoryVisible = $cmsIndex->getTopmenu()->isCategoryVisible($categoryName);
            if ($categoryPermissions[$key]['browsing_category']) {
                \PHPUnit_Framework_Assert::assertTrue(
                    $categoryVisible,
                    'Category is absent on the storefront while it must not.'
                );
            } else {
                \PHPUnit_Framework_Assert::assertFalse(
                    $categoryVisible,
                    'Category is present on the storefront while it must not.'
                );
            }
        }
    }

    /**
     * Returns string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Category is present on the storefront.';
    }
}
