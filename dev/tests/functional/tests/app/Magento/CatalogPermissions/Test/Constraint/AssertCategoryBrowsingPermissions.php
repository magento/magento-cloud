<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Test\Constraint;

use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Catalog\Test\Constraint\AssertCategoryAbsenceOnFrontend;
use Magento\Catalog\Test\Constraint\AssertCategoryPage;

/**
 * Check whether category can be viewed in the storefront according to the category browsing permissions.
 */
class AssertCategoryBrowsingPermissions extends AbstractConstraint
{
    /**
     * Check whether category can be viewed in the storefront.
     *
     * @param BrowserInterface $browser
     * @param CatalogCategoryView $catalogCategoryView
     * @param AssertCategoryPage $assertCategoryPage
     * @param AssertCategoryAbsenceOnFrontend $assertCategoryAbsenceOnFrontend
     * @param array $categories
     * @param array $categoryPermissions
     * @return void
     */
    public function processAssert(
        BrowserInterface $browser,
        CatalogCategoryView $catalogCategoryView,
        AssertCategoryPage $assertCategoryPage,
        AssertCategoryAbsenceOnFrontend $assertCategoryAbsenceOnFrontend,
        array $categories,
        array $categoryPermissions
    ) {
        foreach ($categories as $key => $category) {
            if ($categoryPermissions[$key]['browsing_category']) {
                $assertCategoryPage->processAssert($category, $catalogCategoryView, $browser);
            } else {
                $assertCategoryAbsenceOnFrontend->processAssert(
                    $browser,
                    $catalogCategoryView,
                    $category
                );
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
        return 'Category browsing permissions are working correctly.';
    }
}
