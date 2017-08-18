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
 * Test Creation for CreateBannerEntity
 *
 * Test Flow:
 * Preconditions:
 * 1. Create customer segment
 *
 * Steps:
 * 1. Open Backend
 * 2. Go to Content->Banners
 * 3. Click "Add Banner" button
 * 4. Fill data according to dataset
 * 5. Perform all assertions
 *
 * @group CMS_Content
 * @ZephyrId MAGETWO-25272
 */
class CreateBannerEntityTest extends Injectable
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
     * Inject pages
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
     * Create banner
     *
     * @param Banner $banner
     * @return void
     */
    public function test(Banner $banner)
    {
        // Steps
        $this->bannerIndex->open();
        $this->bannerIndex->getPageActionsBlock()->addNew();
        $this->bannerNew->getBannerForm()->fill($banner);
        $this->bannerNew->getPageMainActions()->save();
    }
}
