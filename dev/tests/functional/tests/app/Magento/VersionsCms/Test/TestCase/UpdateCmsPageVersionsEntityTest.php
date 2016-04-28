<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\TestCase;

use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;
use Magento\VersionsCms\Test\Fixture\Version;

/**
 * Preconditions:
 * 1. Create CMS page under version control.
 * 2. Create custom admin.
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Content > Elements: Pages.
 * 3. Open the page with 'Version Control' = 'Yes'.
 * 4. Open 'Versions' tab.
 * 5. Open version on the top of the grid.
 * 6. Fill fields according to dataset.
 * 7. Click 'Save'.
 * 8. Perform appropriate assertions.
 *
 * @group CMS_Versioning_(PS)
 * @ZephyrId MAGETWO-26960
 */
class UpdateCmsPageVersionsEntityTest extends AbstractVersionsCmsTest
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * Edit Cms Page Versions Entity.
     *
     * @param CmsPage $cms
     * @param Version $version
     * @param string $quantity
     * @return array
     */
    public function test(CmsPage $cms, Version $version, $quantity)
    {
        // Precondition
        $cms->persist();
        // Steps
        $filter = ['title' => $cms->getTitle()];
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $this->cmsPageNew->getPageVersionsForm()->openTab('versions');
        $filter = ['label' => $cms->getTitle()];
        $this->cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()->searchAndOpen($filter);
        $this->cmsVersionEdit->getVersionForm()->fill($version);
        $this->cmsVersionEdit->getFormPageActions()->save();
        return ['results' => [
            'label' => $version->getLabel(),
            'owner' => $version->getUserId(),
            'access_level' => $version->getAccessLevel(),
            'quantity' => $quantity,
            ]
        ];
    }
}
