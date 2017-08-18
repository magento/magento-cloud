<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\TestCase;

use Magento\Banner\Test\Fixture\Banner;
use Magento\Banner\Test\Page\Adminhtml\BannerIndex;
use Magento\Banner\Test\Page\Adminhtml\BannerNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test creation for Delete BannerEntity
 *
 * Test Flow:
 *
 * Preconditions:
 * 1. Create banner
 *
 * 1. Steps:
 * 2. Open Backend
 * 3. Go to Content->Banners
 * 4. Open created banner
 * 5. Click "Delete Banner"
 * 6. Perform all assertions
 *
 * @group CMS_Content
 * @ZephyrId MAGETWO-25644
 */
class DeleteBannerEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * BannerIndex page
     *
     * @var BannerIndex
     */
    protected $bannerIndex;

    /**
     * BannerNew page
     *
     * @var BannerNew
     */
    protected $bannerNew;

    /**
     * Inject data
     *
     * @param BannerIndex $bannerIndex
     * @param BannerNew $bannerNew
     * @return void
     */
    public function __inject(BannerIndex $bannerIndex, BannerNew $bannerNew)
    {
        $this->bannerIndex = $bannerIndex;
        $this->bannerNew = $bannerNew;
    }

    /**
     * Delete banner entity
     *
     * @param Banner $banner
     * @return void
     */
    public function test(Banner $banner)
    {
        // Precondition
        $banner->persist();
        $filter = ['banner' => $banner->getName()];
        // Steps
        $this->bannerIndex->open();
        $this->bannerIndex->getGrid()->searchAndOpen($filter);
        $this->bannerNew->getPageMainActions()->delete();
        $this->bannerNew->getModalBlock()->acceptAlert();
    }
}
