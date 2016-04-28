<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;
use Magento\Cms\Test\Page\Adminhtml\CmsPageIndex;
use Magento\Cms\Test\Page\Adminhtml\CmsPageNew;

/**
 * Assert that created CMS page version can be found on CMS page Versions tab in grid.
 */
class AssertCmsPageVersionInGrid extends AssertCmsPageInitialVersionInGrid
{
    /**
     * Assert that created CMS page version can be found on CMS page Versions tab in grid via:
     * Version label, Owner, Quantity, Access Level.
     *
     * @param CmsPage $cms
     * @param CmsPageNew $cmsPageNew
     * @param CmsPageIndex $cmsPageIndex
     * @param array $results
     * @param CmsPage $cmsInitial[optional]
     * @return void
     */
    public function processAssert(
        CmsPage $cms,
        CmsPageNew $cmsPageNew,
        CmsPageIndex $cmsPageIndex,
        array $results,
        CmsPage $cmsInitial = null
    ) {
        $this->cmsPageIndex = $cmsPageIndex;
        $this->cmsPageNew = $cmsPageNew;
        $filter = ['title' => $cms->getTitle()];
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $this->cmsPageNew->getPageVersionsForm()->openTab('versions');
        $this->searchVersion($cms, $results);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'CMS Page Version is present in grid.';
    }
}
