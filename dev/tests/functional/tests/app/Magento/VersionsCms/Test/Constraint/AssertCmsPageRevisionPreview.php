<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;
use Magento\Cms\Test\Page\Adminhtml\CmsPageIndex;
use Magento\Cms\Test\Page\Adminhtml\CmsPageNew;
use Magento\VersionsCms\Test\Fixture\Revision;
use Magento\VersionsCms\Test\Page\Adminhtml\CmsRevisionEdit;
use Magento\VersionsCms\Test\Page\Adminhtml\CmsRevisionPreview;
use Magento\VersionsCms\Test\Page\Adminhtml\CmsVersionEdit;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that created CMS page revision content can be found in CMS page revisions preview.
 */
class AssertCmsPageRevisionPreview extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that created CMS page revision content can be found in CMS page revisions preview.
     *
     * @param CmsPage $cms
     * @param Revision $revision
     * @param CmsPageIndex $cmsPageIndex
     * @param CmsPageNew $cmsPageNew
     * @param CmsVersionEdit $cmsVersionEdit
     * @param CmsRevisionEdit $cmsRevisionEdit
     * @param CmsRevisionPreview $cmsRevisionPreview
     * @param array $results
     * @return void
     */
    public function processAssert(
        CmsPage $cms,
        Revision $revision,
        CmsPageIndex $cmsPageIndex,
        CmsPageNew $cmsPageNew,
        CmsVersionEdit $cmsVersionEdit,
        CmsRevisionEdit $cmsRevisionEdit,
        CmsRevisionPreview $cmsRevisionPreview,
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
        $cmsVersionEdit->getRevisionsGrid()->searchAndOpen($filter);
        $cmsRevisionEdit->getFormPageActions()->preview();
        $pageContent = $cmsRevisionPreview->getPreviewBlock()->getPageContent();
        $fixtureContent = $revision->getContent();

        \PHPUnit_Framework_Assert::assertEquals(
            $fixtureContent,
            $pageContent,
            'Page content is not equals to expected'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Page content is equal to expected.';
    }
}
