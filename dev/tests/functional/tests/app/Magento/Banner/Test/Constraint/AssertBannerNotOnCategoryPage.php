<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\Banner\Test\Fixture\Banner;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertBannerNotOnCategoryPage
 * Check that banner is absent on specific category page
 */
class AssertBannerNotOnCategoryPage extends AbstractConstraint
{
    /**
     * Assert that banner is absent on specific category page
     *
     * @param CatalogProductSimple $product
     * @param CmsIndex $cmsIndex
     * @param Banner $banner
     * @param CatalogCategoryView $catalogCategoryView
     * @param Customer $customer[optional]
     * @return void
     */
    public function processAssert(
        CatalogProductSimple $product,
        CmsIndex $cmsIndex,
        Banner $banner,
        CatalogCategoryView $catalogCategoryView,
        Customer $customer = null
    ) {
        if ($customer !== null) {
            $this->objectManager->create(
                \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
                ['customer' => $customer]
            )->run();
        } else {
            $cmsIndex->open();
        }
        $cmsIndex->getTopmenu()->selectCategoryByName($product->getCategoryIds()[0]);
        \PHPUnit_Framework_Assert::assertFalse(
            $catalogCategoryView->getBannerViewBlock()->checkWidgetBanners($banner, $customer),
            'Banner is presents on Category page.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return "Banner is absent on Category page.";
    }
}
