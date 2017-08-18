<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\Banner\Test\Fixture\Banner;
use Magento\CatalogRule\Test\Page\Adminhtml\CatalogRuleIndex;
use Magento\CatalogRule\Test\Page\Adminhtml\CatalogRuleNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertBannerNotInCatalogRule
 * Assert that deleted banner is absent on catalog rule creation page and can't be found by name
 */
class AssertBannerNotInCatalogRule extends AbstractConstraint
{
    /**
     * Assert that deleted banner is absent on catalog rule creation page and can't be found by name
     *
     * @param CatalogRuleIndex $catalogRuleIndex
     * @param CatalogRuleNew $ruleNew
     * @param Banner $banner
     * @return void
     */
    public function processAssert(
        CatalogRuleIndex $catalogRuleIndex,
        CatalogRuleNew $ruleNew,
        Banner $banner
    ) {
        $catalogRuleIndex->open();
        $catalogRuleIndex->getGridPageActions()->addNew();
        $ruleNew->getEditForm()->openSection('related_banners');
        $filter = ['banner_name' => $banner->getName()];
        \PHPUnit_Framework_Assert::assertFalse(
            $ruleNew->getEditForm()->getSection('related_banners')->getBannersGrid()->isRowVisible($filter),
            'Banner is present in Catalog Price Rule grid.'
        );
    }

    /**
     * Banner is absent in Catalog Price Rule grid
     *
     * @return string
     */
    public function toString()
    {
        return 'Banner is absent in Catalog Price Rule grid.';
    }
}
