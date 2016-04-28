<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;
use Magento\Cms\Test\Page\Adminhtml\CmsPageIndex;
use Magento\Cms\Test\Page\Adminhtml\CmsPageNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that created CMS page version can not be found on CMS page Versions tab in grid.
 */
class AssertCmsPageVersionNotInGrid extends AbstractConstraint
{
    /**
     * Assert that created CMS page version can not be found on CMS page Versions tab in grid.
     *
     * @param CmsPage $cms
     * @param CmsPageIndex $cmsPageIndex
     * @param CmsPageNew $cmsPageNew
     * @param array $results
     * @return void
     */
    public function processAssert(CmsPage $cms, CmsPageIndex $cmsPageIndex, CmsPageNew $cmsPageNew, array $results)
    {
        $filter = ['title' => $cms->getTitle()];
        $cmsPageIndex->open();
        $cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $cmsPageNew->getPageVersionsForm()->openTab('versions');
        $filter = [
            'label' => $results['label'],
            'owner' => $results['owner'],
            'access_level' => $results['access_level'],
            'quantity' => $results['quantity'],
        ];
        \PHPUnit_Framework_Assert::assertFalse(
            $cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()->isRowVisible(
                $filter,
                false
            ),
            'CMS Page Version with '
            . 'label \'' . $filter['label'] . '\', '
            . 'owner \'' . $filter['owner'] . '\', '
            . 'access level \'' . $filter['access_level'] . '\', '
            . 'quantity \'' . $filter['quantity'] . '\', '
            . 'is present in CMS Page Versions grid.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'CMS Page Version is absent in grid.';
    }
}
