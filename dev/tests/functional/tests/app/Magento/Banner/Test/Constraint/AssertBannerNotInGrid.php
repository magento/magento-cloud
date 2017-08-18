<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\Banner\Test\Fixture\Banner;
use Magento\Banner\Test\Page\Adminhtml\BannerIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertBannerNotInGrid
 * Assert that deleted banner is absent in grid and can't be found by name
 */
class AssertBannerNotInGrid extends AbstractConstraint
{
    /**
     * Assert that deleted banner is absent in grid and can't be found by name
     *
     * @param Banner $banner
     * @param BannerIndex $bannerIndex
     * @return void
     */
    public function processAssert(Banner $banner, BannerIndex $bannerIndex)
    {
        $bannerIndex->open();

        \PHPUnit_Framework_Assert::assertFalse(
            $bannerIndex->getGrid()->isRowVisible(['banner' => $banner->getName()]),
            'Banner is present in banner grid.'
        );
    }

    /**
     * Banner is absent in Banners grid
     *
     * @return string
     */
    public function toString()
    {
        return 'Banner is absent in Banners grid.';
    }
}
