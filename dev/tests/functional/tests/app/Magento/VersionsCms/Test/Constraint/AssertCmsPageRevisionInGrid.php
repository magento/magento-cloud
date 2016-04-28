<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;
use Magento\Cms\Test\Page\Adminhtml\CmsPageIndex;
use Magento\Cms\Test\Page\Adminhtml\CmsPageNew;
use Magento\VersionsCms\Test\Page\Adminhtml\CmsVersionEdit;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that created CMS page revision can be found in CMS page Version Revisions grid.
 */
class AssertCmsPageRevisionInGrid extends AbstractConstraint
{
    /**
     * Assert that created CMS page revision can be found in CMS page Version Revisions grid.
     *
     * @param CmsPage $cms
     * @param CmsPageIndex $cmsPageIndex
     * @param CmsPageNew $cmsPageNew
     * @param CmsVersionEdit $cmsVersionEdit
     * @param array $results
     * @return void
     */
    public function processAssert(
        CmsPage $cms,
        CmsPageIndex $cmsPageIndex,
        CmsPageNew $cmsPageNew,
        CmsVersionEdit $cmsVersionEdit,
        array $results
    ) {
        $filter = ['title' => $cms->getTitle()];
        $cmsPageIndex->open();
        $cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $cmsPageNew->getPageVersionsForm()->openTab('versions');
        $filter = ['label' => $cms->getTitle()];
        $cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()->searchAndOpen($filter);
        $filter = [
            'revision_number_from' => $results['revision_number_from'],
            'revision_number_to' => $results['revision_number_to'],
            'author' => $results['author'],
        ];
        \PHPUnit_Framework_Assert::assertTrue(
            $cmsVersionEdit->getRevisionsGrid()->isRowVisible($filter),
            'CMS Page Revision with '
            . 'revision_number_from \'' . $filter['revision_number_from'] . '\', '
            . 'revision_number_to \'' . $filter['revision_number_to'] . '\', '
            . 'author \'' . $filter['author'] . '\', '
            . 'is not present in CMS Page Revisions grid.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'CMS Page Revision is present in grid.';
    }
}
