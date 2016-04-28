<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\TestCase;

use Magento\VersionsCms\Test\Fixture\Version;
use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;

/**
 * Preconditions:
 * 1. Create CMS page.
 * 2. Edit this Page and add new Version:
 *  - Change Name
 *  - Open Revision and click Publish
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Content > Elements: Pages.
 * 3. Open the page.
 * 4. Open 'Versions' tab.
 * 5. Select the version according to dataset in grid.
 * 6. Select 'Delete' in Versions Mass Actions form.
 * 7. Click 'Submit'.
 * 8. Perform appropriate assertions.
 *
 * @group CMS_Versioning_(PS)
 * @ZephyrId MAGETWO-27096
 */
class MassDeleteCmsVersionsEntityTest extends AbstractVersionsCmsTest
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * Mass Delete Cms Page Versions Entity.
     *
     * @param CmsPage $cms
     * @param Version $version
     * @param array $results
     * @param string $initialVersionToDelete
     * @return array
     */
    public function test(CmsPage $cms, Version $version, array $results, $initialVersionToDelete)
    {
        // Precondition
        $cms->persist();
        $filter = ['title' => $cms->getTitle()];
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $this->cmsPageNew->getPageVersionsForm()->openTab('versions');
        $filter = ['label' => $cms->getTitle()];
        $this->cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()->searchAndOpen($filter);
        $this->cmsVersionEdit->getVersionForm()->fill($version);
        $this->cmsVersionEdit->getFormPageActions()->saveAsNewVersion();

        // Steps
        $filter = ['title' => $cms->getTitle()];
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $this->cmsPageNew->getPageVersionsForm()->openTab('versions');
        $label = $initialVersionToDelete == 'Yes' ? $cms->getTitle() : $version->getLabel();
        $this->cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()
            ->massaction([['label' => $label]], 'Delete', true);

        return [
            'results' => [
                'label' => $label,
                'owner' => $results['owner'],
                'access_level' => $results['access_level'],
                'quantity' => $results['quantity'],
            ]
        ];
    }
}
