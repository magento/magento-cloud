<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\PageCache\Test\Page\Adminhtml\AdminCache;
use Magento\Banner\Test\Page\Adminhtml\BannerIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertBannerSuccessSaveMessage
 * Assert that after banner save "You saved the banner." successful message appears
 */
class AssertBannerSuccessSaveMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    const SUCCESS_SAVE_MESSAGE = 'You saved the banner.';

    /**
     * Assert that after banner save "You saved the banner." successful message appears
     *
     * @param BannerIndex $bannerIndex
     * @param AdminCache $adminCache
     * @return void
     */
    public function processAssert(BannerIndex $bannerIndex, AdminCache $adminCache)
    {
        $actualMessage = $bannerIndex->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_SAVE_MESSAGE,
            $actualMessage,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_SAVE_MESSAGE
            . "\nActual: " . $actualMessage
        );

        $adminCache->open();
        $adminCache->getActionsBlock()->flushCacheStorage();
        $adminCache->getModalBlock()->acceptAlert();
    }

    /**
     * Success message is displayed
     *
     * @return string
     */
    public function toString()
    {
        return 'Success message is displayed.';
    }
}
