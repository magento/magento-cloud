<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\TestCase;

use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;
use Magento\VersionsCms\Test\Fixture\Revision;

/**
 * Preconditions:
 * 1. Create CMS page.
 *
 * Steps:
 * 1. Open Backend.
 * 2. Go to Content > Pages.
 * 3. Find and open created page.
 * 4. Go to Versions tab and open default version.
 * 5. Select 1 revision.
 * 6. Change revision content and click "Save in a new version".
 * 7. Enter version name.
 * 8. Perform all assertions.
 *
 * @group CMS_Versioning_(PS)
 * @ZephyrId MAGETWO-29102
 */
class SaveNewRevisionInNewVersionTest extends AbstractVersionsCmsTest
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * Run UpdateCmsPageRevisionEntity test.
     *
     * @param CmsPage $cms
     * @param Revision $revision
     * @param array $revisionData
     * @param array $results
     * @return void
     */
    public function test(CmsPage $cms, Revision $revision, array $revisionData, array $results)
    {
        // Precondition:
        $cms->persist();
        $title = $cms->getTitle();

        // Steps:
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen(['title' => $title]);
        $cmsPageVersionForm = $this->cmsPageNew->getPageVersionsForm();
        $cmsPageVersionForm->openTab('versions');
        $cmsPageVersionForm->getTab('versions')->getVersionsGrid()->searchAndOpen(['label' => $title]);
        $filter = [
            'revision_number_from' => $revisionData['from'],
            'revision_number_to' => $revisionData['to'],
        ];
        $this->cmsVersionEdit->getRevisionsGrid()->searchAndOpen($filter);
        $this->cmsRevisionEdit->getRevisionForm()->fill($revision);
        $this->cmsRevisionEdit->getFormPageActions()->saveInNewVersion($results['label']);
    }
}
