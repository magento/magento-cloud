<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\TestCase;

use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;

/**
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Content > Elements: Pages.
 * 3. Click 'Add New Page'.
 * 4. Fill fields according to dataset.
 * 6. Click 'Save Page'.
 * 7. Perform appropriate assertions.
 *
 * @group CMS_Versioning_(PS)
 * @ZephyrId MAGETWO-26995
 */
class CreateCmsPageVersionsEntityForNewCmsPageTest extends AbstractVersionsCmsTest
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * Create CMS Page Version Entity.
     *
     * @param CmsPage $cms
     * @return void
     */
    public function test(CmsPage $cms)
    {
        // Steps
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getPageActionsBlock()->addNew();
        $this->cmsPageNew->getPageVersionsForm()->fill($cms);
        $this->cmsPageNew->getPageMainActions()->save();
    }
}
