<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\TestCase;

use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;

/**
 * Preconditions:
 * 1. Create CMS Page.
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Content > Elements: Pages.
 * 3. Open existing page from the grid.
 * 4. Change dropdown value "Under Version Control" to "Yes".
 * 5. Fill fields according to dataset.
 * 6. Click 'Save Page'.
 * 7. Perform appropriate assertions.
 *
 * @group CMS_Versioning_(PS)
 * @ZephyrId MAGETWO-26738
 */
class CreateCmsPageVersionsEntityForExistingCmsPageTest extends AbstractVersionsCmsTest
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * Create CMS Page Version Entity.
     *
     * @param CmsPage $cmsInitial
     * @param CmsPage $cms
     * @return void
     */
    public function test(CmsPage $cmsInitial, CmsPage $cms)
    {
        // Precondition
        $cmsInitial->persist();
        // Steps
        $filter = ['title' => $cmsInitial->getTitle()];
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $this->cmsPageNew->getPageVersionsForm()->fill($cms);
        $this->cmsPageNew->getPageMainActions()->save();
    }
}
