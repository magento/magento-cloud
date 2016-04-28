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
 *
 * Steps:
 * 1. Open Backend.
 * 2. Go to Content > Pages.
 * 3. Find and open created page.
 * 4. Go to Versions tab and open default version.
 * 5. Change version label, access level, user and click "Save as new version".
 * 6. Perform all assertions.
 *
 * @group CMS_Versioning_(PS)
 * @ZephyrId MAGETWO-28574
 */
class SaveNewVersionOfVersionsCmsEntityTest extends AbstractVersionsCmsTest
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * Run Save New Version Of Versions Cms Entity Test.
     *
     * @param CmsPage $cms
     * @param Version $version
     * @param string $quantity
     * @return array
     */
    public function test(CmsPage $cms, Version $version, $quantity)
    {
        // Preconditions:
        $cms->persist();

        // Steps:
        $filter = ['title' => $cms->getTitle()];
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $this->cmsPageNew->getPageVersionsForm()->openTab('versions');
        $filter = ['label' => $cms->getTitle()];
        $this->cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()->searchAndOpen($filter);
        $this->cmsVersionEdit->getVersionForm()->fill($version);
        $this->cmsVersionEdit->getFormPageActions()->saveAsNewVersion();

        return [
            'results' => [
                'label' => $version->getLabel(),
                'owner' => $version->getUserId(),
                'access_level' => $version->getAccessLevel(),
                'quantity' => $quantity,
            ]
        ];
    }
}
