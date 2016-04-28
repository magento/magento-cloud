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
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Assert that link to Currently Published Revision on CMS Page Information Form is available.
 */
class AssertCmsPageCurrentlyPublishedRevision extends AbstractAssertForm
{
    /**
     * Assert that link to Currently Published Revision on CMS Page Information Form is available.
     *
     * @param CmsPage $cms
     * @param CmsPageNew $cmsPageNew
     * @param CmsPageIndex $cmsPageIndex
     * @param array $results
     * @param Revision|null $revision [optional]
     * @return void
     */
    public function processAssert(
        CmsPage $cms,
        CmsPageNew $cmsPageNew,
        CmsPageIndex $cmsPageIndex,
        array $results,
        Revision $revision = null
    ) {
        $filter = ['title' => $cms->getTitle()];
        $cmsPageIndex->open();
        $cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $formPublishedRevision = $cmsPageNew->getPageVersionsForm()->getCurrentlyPublishedRevisionText();
        $fixturePublishedRevision = $cms->getTitle() . '; ' . $results['revision'];
        \PHPUnit_Framework_Assert::assertEquals(
            $fixturePublishedRevision,
            $formPublishedRevision,
            'Link to Currently Published Revision not equals to passed in fixture.'
        );
        $cmsPageNew->getPageVersionsForm()->openTab('content');
        $formRevisionData = $cmsPageNew->getPageVersionsForm()->getTab('revision_content')->getContentData();
        preg_match('/\d+/', $results['revision'], $matches);
        $fixtureRevisionData['revision'] = $matches[0];
        $fixtureRevisionData['version'] = $cms->getTitle();
        $fixtureRevisionData['content'] = $revision !== null
            ? ['content' => $revision->getContent()]
            : $cms->getContent();
        $error = $this->verifyData($fixtureRevisionData, $formRevisionData);
        \PHPUnit_Framework_Assert::assertEmpty($error, $error);
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Link to Currently Published Revision on CMS Page Information Form is available.';
    }
}
