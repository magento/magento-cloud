<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Test\Constraint;

use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Constraint\AssertAddToCartButtonAbsentOnCategoryPage;
use Magento\Checkout\Test\Constraint\AssertAddToCartButtonPresentOnCategoryPage;
use Magento\Checkout\Test\Constraint\AssertAddToCartButtonAbsentOnProductPage;
use Magento\Checkout\Test\Constraint\AssertAddToCartButtonPresentOnProductPage;
use Magento\Cms\Test\Page\CmsIndex;

/**
 * Check whether "Add To Cart" button is visible on category and product pages according to the category permissions.
 */
class AssertAddToCartPermissions extends AbstractConstraint
{
    /**
     * Check whether "Add To Cart" button is visible in the storefront.
     *
     * @param BrowserInterface $browser
     * @param CatalogCategoryView $catalogCategoryView
     * @param CatalogProductView $catalogProductView
     * @param CmsIndex $cmsIndex
     * @param AssertAddToCartButtonAbsentOnCategoryPage $assertAddToCartButtonAbsentCategory
     * @param AssertAddToCartButtonPresentOnCategoryPage $assertAddToCartButtonPresentCategory
     * @param AssertAddToCartButtonAbsentOnProductPage $assertAddToCartButtonAbsentProduct
     * @param AssertAddToCartButtonPresentOnProductPage $assertAddToCartButtonPresentProduct
     * @param array $categories
     * @param array $categoryPermissions
     * @param array $productPermissions
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function processAssert(
        BrowserInterface $browser,
        CatalogCategoryView $catalogCategoryView,
        CatalogProductView $catalogProductView,
        CmsIndex $cmsIndex,
        AssertAddToCartButtonAbsentOnCategoryPage $assertAddToCartButtonAbsentCategory,
        AssertAddToCartButtonPresentOnCategoryPage $assertAddToCartButtonPresentCategory,
        AssertAddToCartButtonAbsentOnProductPage $assertAddToCartButtonAbsentProduct,
        AssertAddToCartButtonPresentOnProductPage $assertAddToCartButtonPresentProduct,
        array $categories,
        array $categoryPermissions,
        array $productPermissions
    ) {
        foreach ($categories as $key => $category) {
            if ($categoryPermissions[$key]['browsing_category']) {
                $products = $category->getDataFieldConfig('category_products')['source']->getProducts();
                foreach ($products as $index => $product) {
                    if ($categoryPermissions[$key]['add_to_cart']) {
                        $assertAddToCartButtonPresentCategory->processAssert(
                            $product,
                            $cmsIndex,
                            $catalogCategoryView,
                            $category
                        );
                    } else {
                        $assertAddToCartButtonAbsentCategory->processAssert(
                            $product,
                            $cmsIndex,
                            $catalogCategoryView,
                            $category
                        );
                    }
                    if ($productPermissions['add_to_cart'][$key][$index]) {
                        $assertAddToCartButtonPresentProduct->processAssert($browser, $product, $catalogProductView);
                    } else {
                        $assertAddToCartButtonAbsentProduct->processAssert($browser, $product, $catalogProductView);
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
        return 'Category add to cart permissions are working correctly.';
    }
}
