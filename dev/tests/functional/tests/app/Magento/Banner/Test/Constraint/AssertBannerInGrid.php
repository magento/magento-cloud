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
 * Class AssertBannerInGrid
 * Assert that created banner is found by name and has correct banner types, visibility, status
 */
class AssertBannerInGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that created banner is found by name and has correct banner types, visibility, status
     *
     * @param Banner $banner
     * @param BannerIndex $bannerIndex
     * @return void
     */
    public function processAssert(Banner $banner, BannerIndex $bannerIndex)
    {
        $bannerIndex->open();
        $filter = [
            'banner' => $banner->getName(),
            'active' => $banner->getIsEnabled(),
        ];

        $storeContent = $banner->getStoreContentsNotUse();
        if (isset($storeContent['value_1']) && $storeContent['value_1'] === 'No') {
            $filter['visibility'] = 'Main Website/Main Website Store/Default Store View';
        }

        $bannerIndex->getGrid()->search($filter);
        if ($banner->hasData('types')) {
            $types = implode(', ', $banner->getTypes());
            $filter['types'] = $types;
        }
        unset($filter['visibility']);

        \PHPUnit_Framework_Assert::assertTrue(
            $bannerIndex->getGrid()->isRowVisible($filter, false),
            'Banner is absent in banner grid.'
        );
    }

    /**
     * Text present Banner in the Banner grid
     *
     * @return string
     */
    public function toString()
    {
        return 'Banner in grid.';
    }
}
