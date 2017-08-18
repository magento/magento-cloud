<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Cms\Test\Page\CmsIndex;

/**
 * Check whether product prices are displayed on the category page according to the category permissions.
 */
class AssertDisplayProductPricesPermissions extends AbstractConstraint
{
    /**
     * Check whether product prices are displayed in the storefront.
     *
     * @param CatalogCategoryView $catalogCategoryView
     * @param CmsIndex $cmsIndex
     * @param AssertPriceOnCategoryPagePresent $assertPriceOnCategoryPagePresent
     * @param AssertPriceOnCategoryPageAbsent $assertPriceOnCategoryPageAbsent
     * @param array $categories
     * @param array $categoryPermissions
     * @return void
     */
    public function processAssert(
        CatalogCategoryView $catalogCategoryView,
        CmsIndex $cmsIndex,
        AssertPriceOnCategoryPagePresent $assertPriceOnCategoryPagePresent,
        AssertPriceOnCategoryPageAbsent $assertPriceOnCategoryPageAbsent,
        array $categories,
        array $categoryPermissions
    ) {
        foreach ($categories as $key => $category) {
            if ($categoryPermissions[$key]['browsing_category']) {
                $products = $category->getDataFieldConfig('category_products')['source']->getProducts();
                foreach ($products as $product) {
                    if ($categoryPermissions[$key]['display_product_prices']) {
                        $assertPriceOnCategoryPagePresent->processAssert(
                            $product,
                            $cmsIndex,
                            $catalogCategoryView,
                            $category
                        );
                    } else {
                        $assertPriceOnCategoryPageAbsent->processAssert(
                            $product,
                            $cmsIndex,
                            $catalogCategoryView,
                            $category
                        );
                    }
                }
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
        return 'Category display product prices permissions are working correctly.';
    }
}
