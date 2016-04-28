<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\TestCase;

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
 * 9. Perform appropriate assertions.
 *
 * @group CMS_Versioning_(PS)
 * @ZephyrId MAGETWO-27566
 */
class UpdateCmsPageRevisionEntityTest extends AbstractVersionsCmsTest
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
     * @return array
     */
    public function test(CmsPage $cms, Revision $revision, array $revisionData, array $results)
    {
        // Precondition:
        $cms->persist();
        $title = $cms->getTitle();

        // Steps:
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen(['title' => $title]);
        $this->cmsPageNew->getPageVersionsForm()->openTab('versions');
        $this->cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()->searchAndOpen(
            ['label' => $title]
        );
        $filter = [
            'revision_number_from' => $revisionData['from'],
            'revision_number_to' => $revisionData['to'],
        ];
        $this->cmsVersionEdit->getRevisionsGrid()->searchAndOpen($filter);
        $this->cmsRevisionEdit->getRevisionForm()->fill($revision);
        $this->cmsRevisionEdit->getFormPageActions()->save();

        $results['label'] = $title;
        return ['results' => $results];
    }
}
