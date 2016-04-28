<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\TestCase;

use Magento\Mtf\Fixture\FixtureFactory;
use Magento\VersionsCms\Test\Fixture\Revision;
use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;

/**
 * Precondition:
 * 1. Create CMS page under version control.
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Content > Elements: Pages.
 * 3. Open the page with 'Version Control' = 'Yes'.
 * 4. Open 'Versions' tab.
 * 5. Open version on the top of the grid.
 * 6. Open a revision specified in dataset.
 * 7. Fill fields according to dataset.
 * 8. Click 'Save'.
 * 9. Open the revision created (expected id is specified in dataset).
 * 10. Click 'Publish'.
 * 11. Perform appropriate assertions.
 *
 * @group CMS_Versioning_(PS)
 * @ZephyrId MAGETWO-27395
 */
class PublishCmsPageRevisionEntityTest extends AbstractVersionsCmsTest
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * Prepare data.
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $cms = $fixtureFactory->createByCode('versionsCmsPage', ['dataset' => 'cms_page_under_version_control']);
        $cms->persist();
        return [
            'cms' => $cms
        ];
    }

    /**
     * Publish cms page revision.
     *
     * @param CmsPage $cms
     * @param Revision $revision
     * @param int $initialRevision
     * @return void
     */
    public function test(CmsPage $cms, Revision $revision, $initialRevision)
    {
        // Steps
        $this->cmsPageIndex->open();
        $title = $cms->getTitle();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen(['title' => $title]);
        $this->cmsPageNew->getPageVersionsForm()->openTab('versions');
        $this->cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()->searchAndOpen(
            ['label' => $title]
        );
        $this->cmsVersionEdit->getRevisionsGrid()->searchAndOpen(['revision_number_from' => 1]);
        $this->cmsRevisionEdit->getRevisionForm()->toggleEditor();
        $this->cmsRevisionEdit->getRevisionForm()->fill($revision);
        $this->cmsRevisionEdit->getFormPageActions()->save();
        $filter = [
            'revision_number_from' => $initialRevision,
            'revision_number_to' => $initialRevision,
        ];
        $this->cmsVersionEdit->getRevisionsGrid()->searchAndOpen($filter);
        $this->cmsRevisionEdit->getFormPageActions()->publish();
    }
}
