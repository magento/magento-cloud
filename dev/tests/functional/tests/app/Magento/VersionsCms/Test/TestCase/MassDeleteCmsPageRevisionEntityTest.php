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
 * Preconditions:
 * 1. Create CMS page.
 * 2. Edit this Page and add new Revision:
 *  - Add Content
 *  - Click Save
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Content > Elements: Pages.
 * 3. Open the page.
 * 4. Open 'Versions' tab.
 * 5. Open Cms Page version.
 * 6. Select Revision according to dataset in grid.
 * 7. Select 'Delete' in Revisions Mass Actions form.
 * 8. Click 'Submit'.
 * 9. Perform appropriate assertions.
 *
 * @group CMS_Versioning_(PS)
 * @ZephyrId MAGETWO-27239
 */
class MassDeleteCmsPageRevisionEntityTest extends AbstractVersionsCmsTest
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * Create Cms Page.
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $cmsPage = $fixtureFactory->createByCode('versionsCmsPage', ['dataset' => 'cms_page_under_version_control']);
        $cmsPage->persist();

        return ['cms' => $cmsPage];
    }

    /**
     * Delete Cms Page Versions Entity.
     *
     * @param CmsPage $cms
     * @param Revision $revision
     * @param array $results
     * @param string $initialRevision
     * @return array
     */
    public function test(CmsPage $cms, Revision $revision, array $results, $initialRevision)
    {
        // Precondition
        $filter = ['title' => $cms->getTitle()];
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $this->cmsPageNew->getPageVersionsForm()->openTab('versions');
        $filter = ['label' => $cms->getTitle()];
        $this->cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()->searchAndOpen($filter);
        $filter = [
            'revision_number_from' => $initialRevision,
            'revision_number_to' => $initialRevision,
            'author' => $results['author'],
        ];
        $this->cmsVersionEdit->getRevisionsGrid()->searchAndOpen($filter);
        $this->cmsRevisionEdit->getRevisionForm()->toggleEditor();
        $this->cmsRevisionEdit->getRevisionForm()->fill($revision);
        $this->cmsRevisionEdit->getFormPageActions()->save();

        // Steps
        $filter = ['title' => $cms->getTitle()];
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $this->cmsPageNew->getPageVersionsForm()->openTab('versions');
        $filter = ['label' => $cms->getTitle()];
        $this->cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()->searchAndOpen($filter);
        $revisions[] = [
            'revision_number_from' => $results['revision_number_from'],
            'revision_number_to' => $results['revision_number_to'],
            'author' => $results['author'],
        ];
        $this->cmsVersionEdit->getRevisionsGrid()->massaction($revisions, 'Delete', true);
    }
}
