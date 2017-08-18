<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\Banner\Test\Fixture\Banner;
use Magento\SalesRule\Test\Page\Adminhtml\PromoQuoteIndex;
use Magento\SalesRule\Test\Page\Adminhtml\PromoQuoteNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertBannerNotInCartRule
 * Assert that deleted banner is absent on shopping cart rule creation page and can't be found by name
 */
class AssertBannerNotInCartRule extends AbstractConstraint
{
    /**
     * Assert that deleted banner is absent on shopping cart rule creation page and can't be found by name
     *
     * @param PromoQuoteIndex $promoQuoteIndex
     * @param PromoQuoteNew $quoteNew
     * @param Banner $banner
     * @return void
     */
    public function processAssert(
        PromoQuoteIndex $promoQuoteIndex,
        PromoQuoteNew $quoteNew,
        Banner $banner
    ) {
        $promoQuoteIndex->open();
        $promoQuoteIndex->getGridPageActions()->addNew();
        $form = $quoteNew->getSalesRuleForm();
        $form->openSection('related_banners');
        $filter = ['banner_name' => $banner->getName()];
        \PHPUnit_Framework_Assert::assertFalse(
            $form->getSection('related_banners')->getBannersGrid()->isRowVisible($filter),
            'Banner is present in Cart Price Rule grid.'
        );
    }

    /**
     * Banner is absent in Cart Price Rule grid
     *
     * @return string
     */
    public function toString()
    {
        return 'Banner is absent in Cart Price Rule grid.';
    }
}
